---
title: Usage - Element exporter plugin for Craft CMS
layout: doc
prev: false
description: Documentation for Element Exporter, a plugin for Craft CMS.
---

# Usage
Element exporter can be set up in a number of ways, 


## Step 1: Element selection
On the first step, you start by selecting the type of Element you want to export. If the element is question has groups (and possibly subgroups), you have to select on of these as well.

These element types and groups are supported out of the box:
- Entry (grouped by Secions and Entry Types) <Badge type="info" text="4.0.0" />
- Categories (grouped by Category groups) <Badge type="info" text="4.0.0" />
- Formie submissions (groups by Form) <Badge type="info" text="4.0.0" />

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
