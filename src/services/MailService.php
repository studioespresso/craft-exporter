<?php

namespace studioespresso\exporter\services;

use Craft;
use craft\base\Component;
use craft\base\Element;
use craft\elements\Category;
use craft\elements\db\ElementQuery;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\mail\Message;
use craft\web\View;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;
use studioespresso\exporter\helpers\FieldTypeHelper;
use studioespresso\exporter\services\formats\Xlsx;
use verbb\formie\Formie;

class MailService extends Component
{
    public function send(ExportElement $export, $path): void
    {
        $exportSettings = $export->getSettings();
        $pluginSettings = Exporter::getInstance()->getSettings();
        if($pluginSettings->emailTemplate) {
            $html = Craft::$app->getView()->renderTemplate(
                $pluginSettings->emailTemplate,
                [],
                View::TEMPLATE_MODE_SITE
            );

        } else {
            $html = Craft::$app->getView()->renderTemplate(
                "exporter/_mail/template",
                [],
                View::TEMPLATE_MODE_CP
            );

        }

        $message = new Message();
        $message->attach($path, ['fileName' => 'Export.xlsx', 'contentType' => 'application/xlsx']);
        $message->setSubject("Your export");
        $message->setTo($exportSettings['email']);
        $message->setHtmlBody($html);

        Craft::$app->getMailer()->send($message);
    }
}
