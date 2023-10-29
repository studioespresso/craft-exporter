<?php

namespace studioespresso\exporter;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\db\Table;
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
use studioespresso\exporter\models\Settings;
use studioespresso\exporter\records\ExportRecord;
use studioespresso\exporter\services\ExportConfigurationService;
use studioespresso\exporter\variables\CraftVariableBehavior;
use yii\base\Event;
use yii\console\Application as ConsoleApplication;

/**
 * Exporter plugin
 *
 * @method static Exporter getInstance()
 * @author Studio Espresso <info@studioespresso.co>
 * @copyright Studio Espresso
 * @license https://craftcms.github.io/license/ Craft License
 **/
class Exporter extends Plugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;
    public bool $hasCpSection = true;

    /**
     * @var null|Exporter
     */
    public static ?Exporter $plugin;


    /**
     * @var mixed|object|null
     */

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
        });

        $this->components = [
            'configuration' => ExportConfigurationService::class,
        ];
    }


    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

//    protected function settingsHtml(): ?string
//    {
//        return Craft::$app->view->renderTemplate('exporter/_settings.twig', [
//            'plugin' => $this,
//            'settings' => $this->getSettings(),
//        ]);
//    }

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
                $event->rules['exporter'] = ['template' => 'exporter/_index'];
                $event->rules['exporter/create'] = 'exporter/element/edit';
                $event->rules['exporter/<elementId:\\d+>/<step:\\d+>'] = 'exporter/element/edit';
            }
        );
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

                // Delete `elements` table rows without corresponding `content` table rows for the custom element
                Craft::$app->getGc()->deletePartialElements(
                    ExportElement::class,
                    Table::CONTENT,
                    'elementId',
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
    }

    private function registerUserPermissions()
    {
        Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, function(RegisterUserPermissionsEvent $event) {
            $event->permissions[] = [
                'heading' => Craft::t('exporter', 'Exporter'),
                'permissions' => [
                    'exporter-createExports' => ['label' => Craft::t('exporter', 'Create forms')],
                    'exporter-deleteExports' => ['label' => Craft::t('exporter', 'Delete forms')],
                ],
            ];
        });
    }
}
