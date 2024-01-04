---
title: Usage - Element exporter plugin for Craft CMS
layout: doc
prev: false
description: Documentation for Element Exporter, a plugin for Craft CMS.
---

# Usage
Element exporter can be set up in a number of ways, 


## Step 1: Element selection
On the first step, you start by selecting the type of Element you want to export. If the element in question has groups (and possibly subgroups), you have to select on of these as well.

These element types and groups are supported out of the box:
- Entry (grouped by Sections and Entry Types) <Badge type="info" text="4.0.0" />
- Categories (grouped by Category groups) <Badge type="info" text="4.0.0" />
- Formie submissions (grouped by Form) <Badge type="info" text="4.0.0" />

<img src="/img/exporter_step_1_no_selection.png" alt="Step 1 of the new export flow">

If the element can be localized, you also have to select the site from which you want to export items. 

## Step 2: Field selection
On the next step, you'll select which attributes &  fields you want to be included in the export.

All custom fields available on the elements in question will be listed, in 1 of 3 ways:
- Without value parsing options (for straight forward values, eg: plain text, email, url)
- With value parsing options (date or time fields, relation fields)
- Not supported fields.

::: tip Not supported fields
 Complex fields, with sub-fields, like Matrix or SuperTable fields are not  supported out of the box. 
You can register a Field Parser to add support yourself.
:::

<img src="/img/exporter_step_2_fields.png" alt="Step 2 of the export creation flow">

## Step 3: Delivery settings
On this step, you can narrow down the number of elements that will be exported, in which format you'd like the export to be generated and to which e-mailaddress the export should be delivered.

### Element selection
You can choose one of the following options, all of those based on the criteria you selected in Step 1
- All elements
- The last x elements created
- Elements created between a certain date and now
- Elements created between 2 dates

### File formats
The plugin supports these file formats:
- JSON <Badge type="info" text="4.0.0" />
- CSV <Badge type="info" text="4.0.0" />

::: info Missing format?
If you need a different file format than the ones listed here, feel free to [create an issue here](https://github.com/studioespresso/craft-exporter/issues) and I'll see if it can be added to the plugin
:::

### Delivery options
You can select to deliver the export in the inbox of the user that is logged in in that moment, or to set one or more email-addresses to which the file will be delivered. 

Depending on the number of elements in your export, generating the file will take longer so when you receive the file depends on that.

::: info Why email?
Since the plugin can't predict how long it will take to generate the file, the safest way to ensure it's delivery is to let Craft's queue system generate the file and when that is done you'll receive an email with the export attached.
:::


