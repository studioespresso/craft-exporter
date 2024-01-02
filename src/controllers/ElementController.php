<?php

namespace studioespresso\exporter\controllers;

use Craft;
use craft\errors\ElementNotFoundException;
use craft\helpers\Json;
use craft\helpers\UrlHelper;
use craft\web\Controller;
use craft\web\View;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;
use studioespresso\exporter\helpers\ElementTypeHelper;
use studioespresso\exporter\jobs\ExportBatchJob;
use yii\web\UnauthorizedHttpException;

class ElementController extends Controller
{
    private ElementTypeHelper $elementHelper;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->elementHelper = Exporter::getInstance()->elements;
        parent::init();
    }

    public function actionRunExport()
    {
        $body = $this->request->getBodyParams();
        $elementId = Craft::$app->getRequest()->getRequiredBodyParam('elementId');
        $export = ExportElement::findOne(['id' => $elementId]);
        if (!$export) {
            throw new ElementNotFoundException();
        }

        $export->settings = Json::encode(array_merge($export->getSettings(), $body['settings']));
        $export->runSettings = Json::encode(array_merge($export->getRunSettings(), $body['runSettings']));

        $export->validate();
        if ($export->getErrors()) {
            /** @phpstan-ignore-next-line */
            Craft::$app->getUrlManager()->setRouteParams([
                "export" => $export,
                "errors" => $export->getErrors(),
            ]);
            return null;
        }

        Craft::$app->getElements()->saveElement($export);
        Craft::debug(
            Craft::t(
                'exporter',
                'Adding "{name}" job to the queue',
                ['name' => $export->name]
            ),
            __METHOD__
        );

        $date = new \DateTime();
        $fileName = "export_{$date->format("d-m-Y-H-i-s")}";

        Craft::$app->getQueue()
            ->ttr(Exporter::$plugin->getSettings()->ttr)
            ->priority(Exporter::$plugin->getSettings()->priority)
            ->push(new ExportBatchJob([
                'elementId' => $export->id,
                'exportName' => $export->name,
                'fields' => $export->getFields(),
                'attributes' => $export->getAttributes(),
                'runSettings' => $export->getRunSettings(),
                'fileName' => $fileName,
            ]));

        $url = UrlHelper::cpUrl("exporter/{$export->id}/watch", ['fileName' => $fileName]);
        return Craft::$app->getResponse()->getHeaders()->set('HX-Redirect', $url);
    }

    public function actionGetDownload()
    {
        $export = ExportElement::findOne(['id' => $this->request->getRequiredBodyParam('exportId')]);
        $settings = $export->getSettings();
        $fileName = $this->request->getRequiredBodyParam('fileName');

        $file = file_get_contents(Craft::$app->getPath()->getTempPath() . "/{$fileName}.{$settings['fileType']}");
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }

    public function actionWatch($elementId = null)
    {
        $element = null;
        if ($elementId) {
            $element = ExportElement::find()->id($elementId)->one();
        }

        return $this->renderTemplate('exporter/_export/_running', [
            'export' => $element,
        ], View::TEMPLATE_MODE_CP);
    }

    /**
     * @param $elementId
     * @return \yii\web\Response
     * @throws UnauthorizedHttpException
     * @throws \Throwable
     */
    public function actionRun($elementId = null): \yii\web\Response
    {
        $element = null;
        if ($elementId) {
            $element = ExportElement::find()->id($elementId)->one();
        }

        return $this->renderTemplate('exporter/_export/_run', [
            'export' => $element,
        ], View::TEMPLATE_MODE_CP);
    }

    /**
     * @param $elementId
     * @return \yii\web\Response
     * @throws UnauthorizedHttpException
     * @throws \Throwable
     */
    public function actionEdit($elementId = null, $step = 1): \yii\web\Response
    {
        if (!Craft::$app->getUser()->getIdentity()->can('exporter-createExports')) {
            throw new UnauthorizedHttpException("You are not authorized to create new exports");
        }

        $element = null;

        if ($elementId) {
            $element = ExportElement::find()->id($elementId)->one();
        }
        return $this->renderTemplate('exporter/_export/_edit', [
            'export' => $element,
            'elementTypeOptions' => $this->elementHelper->getElementTypesOnly(),
            'step' => $step,
        ], View::TEMPLATE_MODE_CP);
    }

    public function actionStep1()
    {
        $body = $this->request->getBodyParams();
        if (!isset($body['elementId'])) {
            $export = new ExportElement();
        } else {
            $export = ExportElement::findOne(['id' => $body['elementId']]);
        }


        $export->name = $body['name'];
        $export->elementType = $body['elementType'];
        $export->settings = Json::encode(array_merge($export->getSettings(), $body['settings']));


        $export->setScenario(ExportElement::STEP_1);

        $export->validate();
        if ($export->getErrors()) {
            /** @phpstan-ignore-next-line */
            Craft::$app->getUrlManager()->setRouteParams([
                "export" => $export,
                "errors" => $export->getErrors(),
            ]);
            return null;
        }

        Craft::$app->getElements()->saveElement($export);

        $url = UrlHelper::cpUrl("exporter/{$export->id}/2");
        return Craft::$app->getResponse()->getHeaders()->set('HX-Redirect', $url);
    }

    public function actionStep2()
    {
        $body = $this->request->getBodyParams();
        $elementId = Craft::$app->getRequest()->getRequiredBodyParam('elementId');
        $export = ExportElement::findOne(['id' => $elementId]);
        if (!$export) {
            throw new ElementNotFoundException();
        }

        $attributes = array_filter($body['attributes']);
        $export->attributes = Json::encode($attributes);

        $fields = array_filter($body['fields']);
        $export->fields = Json::encode($fields);

        $export->setScenario(ExportElement::STEP_2);


        $export->validate();
        $selectedFields = $export->getSelectedFields();

        if(!$selectedFields) {
            $export->addError('fields', Craft::t('exporter', 'Please select at least one field to export'));
        }

        if ($export->getErrors()) {
            /** @phpstan-ignore-next-line */
            Craft::$app->getUrlManager()->setRouteParams([
                "export" => $export,
                "errors" => $export->getErrors(),
            ]);
            return null;
        }

        Craft::$app->getElements()->saveElement($export);

        $url = UrlHelper::cpUrl("exporter/{$export->id}/run");
        return Craft::$app->getResponse()->getHeaders()->set('HX-Redirect', $url);
    }

    public function actionStep3()
    {
        $body = $this->request->getBodyParams();
        $elementId = Craft::$app->getRequest()->getRequiredBodyParam('elementId');
        $export = ExportElement::findOne(['id' => $elementId]);
        if (!$export) {
            throw new ElementNotFoundException();
        }

        $export->settings = Json::encode(array_merge($export->getSettings(), $body['settings']));

        $export->validate();
        if ($export->getErrors()) {
            /** @phpstan-ignore-next-line */
            Craft::$app->getUrlManager()->setRouteParams([
                "export" => $export,
                "errors" => $export->getErrors(),
            ]);
            return null;
        }

        Craft::$app->getElements()->saveElement($export);

        $url = UrlHelper::cpUrl("exporter/{$export->id}/4");
        return Craft::$app->getResponse()->getHeaders()->set('HX-Redirect', $url);
    }
}
