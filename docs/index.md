---
title: Element exporter plugin for Craft CMS
layout: doc
prev: false
description: Documentation for Element Exporter, a plugin for Craft CMS.
---
# Element exporter for Craft CMS
Element Exporter aims to make exporting data from your Craft CMS website easy, repeatable and includes event to allow for custom data parsing and custom element support. 

![Banner](./images/banner.png)


## Requirements
This plugin requires Craft CMS 4.5.0 or later.


## Installation
To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

```bash
# go to the project directory
cd /path/to/my-craft-project.dev

# tell Composer to install the plugin
composer require studioespresso/craft-exporter

# tell Craft to install the plugin
./craft install/plugin exporter
```

## Roadmap

The following additions & improvements are being considered:
- Freeform support
- Export elements related to another element
- Add the option to compress the export before it gets attached to the email
- Add the option to download the file directly when the export is done.

[Get in touch](https://github.com/studioespresso/craft-exporter/discussions/categories/ideas) if there's anything elso you'd like to see added!

Brought to you by [Studio Espresso](https://studioespresso.co/)
