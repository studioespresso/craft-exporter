<?php

namespace studioespresso\exporter\elements;

use Craft;
use craft\base\Element;
use craft\elements\db\ElementQueryInterface;
use craft\elements\User;
use craft\helpers\Db;
use studioespresso\exporter\elements\db\ExportElementQuery;
use studioespresso\exporter\records\ExportRecord;

class ExportElement extends Element
{
    public $name;

    public $elementType;

    public $settings;

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

    protected static function defineTableAttributes(): array
    {
        return [
            'name' => \Craft::t('exporter', 'Name'),
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
                'criteria' => []
            ]
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
        return sprintf('exporter/%s', $this->getCanonicalId());
    }


    public function afterSave(bool $isNew): void
    {
        if (!$this->propagating) {
            Db::upsert(ExportRecord::tableName(), [
                'id' => $this->id,
                'name' => $this->name,
                'elementType' => $this->elementType,
            ], [
                'name' => $this->name,
                'elementType' => $this->elementType,
            ]);
        }

        parent::afterSave($isNew);
    }
}
