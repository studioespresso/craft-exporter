<?php

namespace studioespresso\exporter;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\db\Table;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\User;
use craft\events\DefineBehaviorsEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\services\Elements;
use craft\services\Gc;
use craft\services\UserPermissions;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use putyourlightson\sprig\Sprig;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\events\RegisterExportableElementTypes;
use studioespresso\exporter\events\RegisterExportableFieldTypes;
use studioespresso\exporter\fields\DateTimeParser;
use studioespresso\exporter\fields\FormieNameParser;
use studioespresso\exporter\fields\MultiOptionsFieldParser;
use studioespresso\exporter\fields\OptionsFieldParser;
use studioespresso\exporter\fields\PlainTextParser;
use studioespresso\exporter\fields\RelationFieldParser;
use studioespresso\exporter\helpers\ElementTypeHelper;
use studioespresso\exporter\helpers\FieldTypeHelper;
use studioespresso\exporter\models\ExportableCategoryModel;
use studioespresso\exporter\models\ExportableEntryModel;
use studioespresso\exporter\models\ExportableFormieSubmissionModel;
use studioespresso\exporter\models\ExportableUserModel;
use studioespresso\exporter\models\Settings;
use studioespresso\exporter\records\ExportRecord;
use studioespresso\exporter\services\ElementService;
use studioespresso\exporter\services\ExportQueryService;
use studioespresso\exporter\services\MailService;
use studioespresso\exporter\variables\CraftVariableBehavior;
use studioespresso\exporter\variables\ExporterVariable;
use yii\base\Event;
use yii\console\Application as ConsoleApplication;

/**
 * Exporter plugin
 *
 * @method static Exporter getInstance()
 * @author Studio Espresso <info@studioespresso.co>
 * @copyright Studio Espresso
 * @license https://craftcms.github.io/license/ Craft License
 *
 * @property-read ExportQueryService $query
 * @property-read ElementTypeHelper $elements
 * @property-read FieldTypeHelper $fields
 * @property-read MailService $mail
 * @property-read ElementService $element
 **/
class Exporter extends Plugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;
    public bool $hasCpSection = true;

    private $_settings;

    /**
     * @var null|Exporter
     */
    public static ?Exporter $plugin;

    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        if (Craft::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'studioespresso\exporter\console\controllers';
        }

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            Sprig::bootstrap();
            $this->registerElementTypes();
            $this->attachEventHandlers();
            $this->registerCpRoutes();
            $this->registerUserPermissions();
            $this->registerSupportedElementTypes();
            // Register plugin support
            $this->registerCkEditor();
            $this->registerRedactor();
            $this->registerFormie();
        });
    }

    public static function config(): array
    {
        return [
            'components' => [
                'elements' => ['class' => ElementTypeHelper::class],
                'fields' => ['class' => FieldTypeHelper::class],
                'query' => ['class' => ExportQueryService::class],
                'mail' => ['class' => MailService::class],
                'element' => ['class' => ElementService::class],
            ],
        ];
    }

    public function getSettings(): ?Settings
    {
        if (!isset($this->_settings)) {
            $this->_settings = $this->createSettingsModel() ?: false;
        }

        return $this->_settings ?: null;
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('exporter/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    public function getCpNavItem(): array
    {
        $ret = [
            'label' => $this->getSettings()->pluginLabel,
            'url' => $this->id,
        ];
        if (($iconPath = $this->cpNavIconPath()) !== null) {
            $ret['icon'] = $iconPath;
        }
        return $ret;
    }

    private function registerElementTypes(): void
    {
        Event::on(Elements::class, Elements::EVENT_REGISTER_ELEMENT_TYPES,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = ExportElement::class;
            });
    }

    private function registerCpRoutes(): void
    {
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function(RegisterUrlRulesEvent $event) {
                $event->rules['exporter'] = 'exporter/element/index';
                $event->rules['exporter/create'] = 'exporter/element/edit';
                $event->rules['exporter/<elementId:\\d+>/<step:\\d+>'] = 'exporter/element/edit';
                $event->rules['exporter/<elementId:\\d+>/run'] = 'exporter/element/run';
                $event->rules['exporter/<elementId:\\d+>/watch'] = 'exporter/element/watch';
            }
        );
    }

    private function registerSupportedElementTypes()
    {
        Event::on(
            ElementTypeHelper::class,
            ElementTypeHelper::EVENT_REGISTER_EXPORTABLE_ELEMENT_TYPES,
            function(RegisterExportableElementTypes $event) {
                $entryModel = new ExportableEntryModel();
                $categoryModel = new ExportableCategoryModel();
                $userModel = new ExportableUserModel();
                $event->elementTypes = array_merge($event->elementTypes, [
                    Entry::class => $entryModel,
                    Category::class => $categoryModel,
                    User::class => $userModel,
                ]);
            });
    }

    private function attachEventHandlers(): void
    {
        Event::on(
            Gc::class,
            Gc::EVENT_RUN,
            function(Event $event) {
                // Delete `elements` table rows without peers in our custom products table
                Craft::$app->getGc()->deletePartialElements(
                    ExportElement::class,
                    ExportRecord::tableName(),
                    'id',
                );
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_DEFINE_BEHAVIORS,
            function(DefineBehaviorsEvent $e) {
                $e->sender->attachBehaviors([
                    CraftVariableBehavior::class,
                ]);
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function(Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('exporter', ExporterVariable::class);
            }
        );
    }

    private function registerUserPermissions()
    {
        Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function(RegisterUserPermissionsEvent $event) {
            $event->permissions[] = [
                'heading' => Craft::t('exporter', 'Exporter'),
                'permissions' => [
                    'exporter-createExports' => ['label' => Craft::t('exporter', 'Create exports')],
                    'exporter-deleteExports' => ['label' => Craft::t('exporter', 'Delete exports')],
                ],
            ];
        });
    }

    private function registerRedactor()
    {
        if (Craft::$app->getPlugins()->isPluginEnabled('redactor')) {
            Event::on(
                FieldTypeHelper::class,
                FieldTypeHelper::EVENT_REGISTER_EXPORTABLE_FIELD_TYPES,
                function(RegisterExportableFieldTypes $event) {
                    $parsers = $event->fieldTypes;
                    $parsers[PlainTextParser::class][] = \craft\redactor\Field::class; // @phpstan-ignore-line
                    $event->fieldTypes = $parsers;
                });
        }
    }

    private function registerCkEditor()
    {
        if (Craft::$app->getPlugins()->isPluginEnabled('ckeditor')) {
            Event::on(
                FieldTypeHelper::class,
                FieldTypeHelper::EVENT_REGISTER_EXPORTABLE_FIELD_TYPES,
                function(RegisterExportableFieldTypes $event) {
                    $parsers = $event->fieldTypes;
                    $parsers[PlainTextParser::class][] = \craft\ckeditor\Field::class; // @phpstan-ignore-line
                    $event->fieldTypes = $parsers;
                });
        }
    }


    private function registerFormie()
    {
        // Register support for Formie if the plugin is installed and Enabled
        if (Craft::$app->getPlugins()->isPluginEnabled('formie')) {
            Event::on(
                ElementTypeHelper::class,
                ElementTypeHelper::EVENT_REGISTER_EXPORTABLE_ELEMENT_TYPES,
                function(RegisterExportableElementTypes $event) {
                    $model = new ExportableFormieSubmissionModel();
                    $event->elementTypes = array_merge($event->elementTypes, [
                        /** @phpstan-ignore-next-line */
                        \verbb\formie\elements\Submission::class => $model,
                    ]);
                });

            Event::on(
                FieldTypeHelper::class,
                FieldTypeHelper::EVENT_REGISTER_EXPORTABLE_FIELD_TYPES,
                function(RegisterExportableFieldTypes $event) {
                    $parsers = $event->fieldTypes;

                    $event->fieldTypes[PlainTextParser::class] = array_merge($parsers[PlainTextParser::class], [
                        \verbb\formie\fields\formfields\Email::class, // @phpstan-ignore-line
                        \verbb\formie\fields\formfields\SingleLineText::class, // @phpstan-ignore-line
                        \verbb\formie\fields\formfields\MultiLineText::class, // @phpstan-ignore-line
                        \verbb\formie\fields\formfields\Phone::class, // @phpstan-ignore-line
                        \verbb\formie\fields\formfields\Agree::class, // @phpstan-ignore-line
                        \verbb\formie\fields\formfields\Number::class, // @phpstan-ignore-line
                    ]);

                    $event->fieldTypes[DateTimeParser::class] = array_merge($parsers[DateTimeParser::class], [
                        \verbb\formie\fields\formfields\Date::class, // @phpstan-ignore-line
                    ]);

                    $event->fieldTypes[OptionsFieldParser::class] = array_merge($parsers[OptionsFieldParser::class], [
                        \verbb\formie\fields\formfields\Radio::class, // @phpstan-ignore-line
                        \verbb\formie\fields\formfields\Dropdown::class, // @phpstan-ignore-line
                    ]);

                    $event->fieldTypes[MultiOptionsFieldParser::class] = array_merge($parsers[MultiOptionsFieldParser::class], [
                        \verbb\formie\fields\formfields\Checkboxes::class, // @phpstan-ignore-line
                    ]);

                    $event->fieldTypes[RelationFieldParser::class] = array_merge($parsers[RelationFieldParser::class], [
                        \verbb\formie\fields\formfields\Entries::class, // @phpstan-ignore-line
                        \verbb\formie\fields\formfields\Categories::class, // @phpstan-ignore-line
                    ]);

                    $event->fieldTypes = array_merge($event->fieldTypes, [FormieNameParser::class => [
                        \verbb\formie\fields\formfields\Name::class, // @phpstan-ignore-line
                    ]]);
                });
        }
    }
}
