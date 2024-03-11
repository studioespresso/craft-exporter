---
title: Elements, fields & formats - Element exporter plugin for Craft CMS
layout: doc
prev: false
description: Documentation for Element Exporter, a plugin for Craft CMS.
---

# Elements, fields & formats
This is an overview, per element type, of the fields that can be exported out of the box.
Versions of the plugin in which support is added are listed, as well as versions in which certain value options are added as well.


## Supported elements
- Craft Entries
- Craft Categories
- [Formie Submissions](https://verbb.io/craft-plugins/formie/features)


## Output formats
The plugins supports generating files in the following formats:
- XLSX <Badge type="info" text="4.0.0" />
- CSV <Badge type="info" text="4.0.0" />

::: info Missing format?
If you need a different file format than the ones listed here, feel free to [create an issue here](https://github.com/studioespresso/craft-exporter/issues) and I'll see if it can be added to the plugin
:::

## Field Parsers
The handle fields, the plugin come with a number of "parser". The selected parser defines how the data will be added to the export and if there are any formatting options available for the data in question.


### RelationFieldParser <Badge type="info" text="4.0.0" />
- Used for any relation fields
- Output options are: `title`, `id`, `url`

### OptionsFieldParser <Badge type="info" text="4.0.0" />
- Used for any single-value select fields (dropdown, radio buttons)
- Output options are: `label`, `value`

### MultiOptionsFieldParser <Badge type="info" text="4.0.0" />
- Used for any multi-value select fields (checkboxes)
- Output options are: `label`, `value`

### OptionsFieldParser <Badge type="info" text="4.0.0" />
- Used for any dropdown or select fields
- Output options are: `label`, `value`

### DateTimeParser <Badge type="info" text="4.0.0" />
- Used for Date and DateTime fields
- Output options are: `d/M/Y`, `m/d/Y`, `d/M/Y H:i`, `m/d/Y H:i`, `d/M/Y H:i:s`, `m/d/Y H:i:s`

### TimeParser <Badge type="info" text="4.0.0" />
- Used for Date and DateTime fields
- Output options are: `H:i:s`, `H:i`

### PlainTextParser <Badge type="info" text="4.0.0" />
Straight forward, this output the data as it's entered.
This is also the fallback for any data that can't be process by the other parsers.