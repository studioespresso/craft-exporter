---
title: Custom element support - Element exporter plugin for Craft CMS
layout: doc
prev: false
description: Documentation for Element Exporter, a plugin for Craft CMS.
---

# Registering support for new element types

## Step 1: ``ExportableElementTypeModel``

Registering support for new element types is done throught a ``ExportableElementTypeModel`` model.

In the example below, we register support for Formie Submissions:

````php
class ExportableFormieSubmissionModel extends ExportableElementTypeModel
{
    /** @phpstan-ignore-next-line */
    public $elementType = Submission::class;

    public string $elementLabel = "Formie Submissions";

    public function getGroup(): array
    {
        return [
            "label" => "Form",
            "parameter" => "formId",
            "items" => Formie::getInstance()->getForms()->getAllForms(), // @phpstan-ignore-line
            "nameProperty" => "title",
        ];
    }

    public function getSubGroup(): bool|array
    {
        return false;
    }
}
````

In this model, we defined the following properties:

### ``elementType``
The element type's class you want to export


### ``elementLabel``
How you want the element type to be labeled when creating a new export

### ``getGroup()``
In this function we defined how the elements are grouped or divided. 
This function should return an array with the following items:
- ``label``: what the grouping is called
- ``parameter``: the property on the element that contains the id referencing the group
- ``items``: this should call the function that gets all group options.
- ``nameProperty``: under which property the name of the group can be found (name, title, etc)

::: warning Permissions for groups
Note that when registering a new element type, the ``items`` property should take into account the current user's permissions to see those elements. 
:::

---

## Step 2: Register our model

Once you've created the model, you need to register it through the following event:

You add to the current supported types by adding ``\plugin\namespace\elements\class => $model`` to the current array.

````php
use studioespresso\exporter\helpers\ElementTypeHelper;
use studioespresso\exporter\events\RegisterExportableElementTypes;

 Event::on(
    ElementTypeHelper::class,
    ElementTypeHelper::EVENT_REGISTER_EXPORTABLE_ELEMENT_TYPES,
    function(RegisterExportableElementTypes $event) {
        $model = new ExportableFormieSubmissionModel();
        $event->elementTypes = array_merge($event->elementTypes, [
            \verbb\formie\elements\Submission::class => $model,
        ]);
    }
);
````
