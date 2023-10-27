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
use studioespresso\exporter\jobs\ExportJob;
use studioespresso\exporter\services\ExportConfigurationService;
use yii\web\UnauthorizedHttpException;

class ElementController extends Controller
{
    private ExportConfigurationService $config;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->config = Exporter::getInstance()->configuration;
        parent::init();
    }

    public function actionRunExport()
    {
        $elementId = $this->request->getRequiredBodyParam('elementId');
        $export = ExportElement::findOne(['id' => $elementId]);
        Craft::debug(
            Craft::t(
                'exporter',
                'Adding "{name}" job to the queue',
                ['name' => $export->name]
            ),
            __METHOD__
        );
        Craft::$app->getQueue()
            ->ttr(Exporter::$plugin->getSettings()->ttr)
            ->priority(Exporter::$plugin->getSettings()->priority)
            ->push(new ExportJob([
            'elementId' => $export->id,
            'exportName' => $export->name
        ]));
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

        if($elementId) {
            $element = ExportElement::find()->id($elementId)->one();
        }
        return $this->renderTemplate('exporter/_export/_edit', [
            'export' => $element,
            'elementTypeOptions' => $this->config->getAvailableElementTypes(),
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
        $export->settings = Json::encode($body['settings']);

        if(!$export->validate()) {
            dd($export->getErrors());
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
        Craft::$app->getElements()->saveElement($export);

        $url = UrlHelper::cpUrl("exporter/{$export->id}/3");
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
        Craft::$app->getElements()->saveElement($export);

        $url = UrlHelper::cpUrl("exporter/{$export->id}/4");
        return Craft::$app->getResponse()->getHeaders()->set('HX-Redirect', $url);
    }
}
