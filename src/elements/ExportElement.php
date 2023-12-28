<?php

namespace studioespresso\exporter\elements;

use Craft;
use craft\base\Element;
use craft\elements\db\ElementQuery;
use craft\elements\db\ElementQueryInterface;
use craft\elements\User;
use craft\helpers\Db;
use craft\helpers\Json;
use studioespresso\exporter\elements\db\ExportElementQuery;
use studioespresso\exporter\Exporter;
use studioespresso\exporter\records\ExportRecord;

class ExportElement extends Element
{
    public $name;

    public $elementType;

    public $settings;

    public $attributes;

    public $fields;

    public $runSettings;

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('exporter', 'Export');
    }

    /**
     * @inheritdoc
     */
    public static function lowerDisplayName(): string
    {
        return Craft::t('exporter', 'export');
    }

    /**
     * @inheritdoc
     */
    public static function pluralDisplayName(): string
    {
        return Craft::t('exporter', 'Exports');
    }

    /**
     * @inheritdoc
     */
    public static function pluralLowerDisplayName(): string
    {
        return Craft::t('exporter', 'Exports');
    }

    /**
     * @inheritdoc
     */
    public static function refHandle(): ?string
    {
        return 'export';
    }

    /**
     * @inheritdoc
     */
    public static function trackChanges(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function hasContent(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function hasTitles(): bool
    {
        return false;
    }

    public static function hasStatuses(): bool
    {
        return false;
    }

    public static function isLocalized(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function canView(User $user): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function canSave(User $user): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function canDuplicate(User $user): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function canDelete(User $user): bool
    {
        return Craft::$app->getUser()->getIdentity()->can('exporter-deleteExports');
    }

    public function scenarios()
    {
        return [
            'step1' => ['name', 'group'],
            'default' => [],
        ];
    }

    /**
     * @param null $attributeNames
     * @param true $clearErrors
     * @inheritDoc
     */
    public function validate($attributeNames = null, $clearErrors = true): bool
    {

        if ($this->scenario == 'step1') {
            $settings = $this->getSettings();
            if (!$settings['group']) {
                $this->addError("group", "Group cannot be blank");
            }
        }
        return parent::validate();

    }


    public function getGroupLabel()
    {
        $elementSettings = Exporter::getInstance()->elements->getElementTypeSettings($this->elementType);
        $settings = $this->getSettings();
        $group = array_filter($elementSettings['group']['items'], function ($group) use ($settings) {
            if ($group->id == $settings['group']) {
                return true;
            }
            return false;
        });
        $item = reset($group);
        return isset($item->title) ? $item->title : $item->name;
    }

    public function getElementLabel()
    {
        $array = explode("\\", $this->elementType);
        return end($array);
    }

    protected static function defineTableAttributes(): array
    {
        return [
            'elementType' => Craft::t('exporter', 'Element'),
            'groupLabel' => "Group",
            'elementLabel' => "Element",
        ];
    }

    protected static function defineSources(string $context = null): array
    {
        return [
            [
                'key' => '*',
                'label' => 'All exports',
                'criteria' => [],
            ],
        ];
    }

    public static function find(): ElementQueryInterface
    {
        return new ExportElementQuery(static::class);
    }

    public function getUiLabel(): string
    {
        return $this->name;
    }

    protected static function includeSetStatusAction(): bool
    {
        return false;
    }

    protected function previewTargets(): array
    {
        return [];
    }

    protected function cpEditUrl(): ?string
    {
        if ($this->isReadyToRun()) {
            return sprintf('exporter/%s/run', $this->getCanonicalId());
        }
        return sprintf('exporter/%s/1', $this->getCanonicalId());
    }

    public function getElementQuery(): ElementQuery
    {
        return Exporter::$plugin->query->buildQuery($this);
    }

    public function getSupportedFields(Element $element): array
    {
        $supportedFields = Exporter::getInstance()->fields->getAvailableFieldTypes();
        $elementFields = $element->fieldLayout->getCustomFields();

        return array_filter($elementFields, function ($field) {
            // TODO How to handle unsupported fields here?
            return true;

        });
    }

    public function getSettings(): null|array
    {
        return Json::decode($this->settings) ?? [];
    }

    public function getAttributes($names = null, $except = []): null|array
    {
        return Json::decode($this->attributes);
    }

    public function getFields(): null|array
    {
        return Json::decode($this->fields);
    }

    public function getRunSettings(): null|array
    {
        return Json::decode($this->runSettings) ?? [];
    }

    public function getHeadings(): array
    {
        $headings = [];
        foreach ($this->getFields() as $field) {
            if (is_array($field)) {
                $headings[] = $field['handle'];
            } else {
                $headings[] = $field;
            }
        }
        return $headings;
    }

    public function isReadyToRun(): bool
    {
        return ($this->getSettings() && $this->getFields());
    }

    public function parseFieldValues(Element $element): array
    {
        return Exporter::$plugin->query->getFields($this, $element);
    }

    protected function defineRules(): array
    {
        $rules = parent::defineRules();
        $rules[] = [['name', 'elementType'], 'required'];

        return $rules;
    }


    public function afterSave(bool $isNew): void
    {
        if (!$this->propagating) {
            Db::upsert(ExportRecord::tableName(), [
                'id' => $this->id,
                'name' => $this->name,
                'elementType' => $this->elementType,
                'settings' => $this->settings,
                'attributes' => $this->attributes,
                'fields' => $this->fields,
                'runSettings' => $this->runSettings,
            ], [
                'name' => $this->name,
                'elementType' => $this->elementType,
                'settings' => $this->settings,
                'attributes' => $this->attributes,
                'fields' => $this->fields,
                'runSettings' => $this->runSettings,
            ]);
        }

        parent::afterSave($isNew);
    }

    /**
     * @inheritDoc
     */
    public function afterDelete(): void
    {
        if (!$this->propagating) {
            Db::delete(ExportRecord::tableName(), [
                    'id' => $this->id,]
            );
        }
        parent::afterDelete();
    }
}
