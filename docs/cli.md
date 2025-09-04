---
title: Element exporter plugin for Craft CMS
prev: false
description: Documentation for Element Exporter, a plugin for Craft CMS.
---
# Scheduling exports via CLI

The plugin comes with a command line tool that can be used to schedule exports via cron-jobs or other task schedulers. 

````bash
php craft exporter/export/run {elementId}
````

---

Searching by ID is not always the most convenient way to find the element you want to export. 

You can add use the following code to search for your specific export and then use the plugin's service to push the job to the queue

````php
$export = ExportElement::find()->name("My export name")->one();
Exporter::getInstance()->queue->addExport($export);
```` 

 