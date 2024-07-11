<?php

namespace studioespresso\exporter\models;

use craft\elements\Entry;
use craft\elements\User;
use craft\services\Sections;

class ExportableUserModel extends ExportableElementTypeModel
{
    /**
     * References the class of the Element Type
     * @var string
     */
    public $elementType = User::class;

    /**
     * Label of the element type
     * @var string
     */
    public string $elementLabel = "Users";


    /**
     * This function defines the element's attributes you want to make exportable
     * @return array
     */
    public function getElementAttributes(): bool|array
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'fullName' => 'Full Name',
            'dateCreated' => 'Date created',
        ];
    }

    /**
     * This function defines the groups in which the element can be groups. Like sections for entries, forms for submissions , etc
     * @return array
     */
    public function getGroup(): array
    {
        return [
            "parameter" => "groupId",
            "label" => \Craft::t('exporter', 'User Group'),
            "instructions" => \Craft::t('exporter', 'Choose a group from which you want to start your export'),
            "items" => $this->getAuthorizedGroups(),
        ];
    }

    /**
     * If the element's groups have an additional sub-group, define those here
     * @return bool|array
     */
    public function getSubGroup(): array|bool
    {
        return false;
    }

    private function getAuthorizedGroups()
    {
        if(\Craft::$app->getRequest()->isConsoleRequest) {
            $currentUser = User::findOne(['admin' => 1]);
        } else {
            $currentUser = \Craft::$app->getUser()->getIdentity();
        }
        $groups = \Craft::$app->getUserGroups()->getAllGroups();

        // If the current user is an admin, they can export all groups
        if ($currentUser->admin) {
            return collect($groups)->map(function ($group) {
                return ['id' => $group->id, 'name' => $group->name];
            })->toArray();
        }

        $canEditUsers = \Craft::$app->getUserPermissions()->doesUserHavePermission($currentUser->id, 'editUsers') || $currentUser->admin;
        dd($canEditUsers);

    }
}
