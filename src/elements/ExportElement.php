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
    public function canDelete(User $user): bool
    {
        return Craft::$app->getUser()->getIdentity()->can('exporter-deleteExports');
    }

    protected static function defineTableAttributes(): array
    {
        return [
            'elementType' => Craft::t('exporter', 'Element'),
        ];
    }

    public static function find(): ElementQueryInterface
    {
        return new ExportElementQuery(static::class);
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

    public function getElementQuery(): ElementQuery
    {
        return Exporter::$plugin->query->buildQuery($this);
    }

    public function getSupportedFields(Element $element): array
    {
        $supportedFields = Exporter::getInstance()->configuration->getSupportedFieldTypes();
        $elementFields = $element->fieldLayout->getCustomFields();

        return array_filter($elementFields, function ($field) use ($supportedFields) {
            d(get_class($field));
            //return in_array(get_class($field), $supportedFields);
        });
        exit;
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

    public function isReadyToRun(): bool
    {
        return false;
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
}
