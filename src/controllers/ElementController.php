<?php

namespace studioespresso\exporter\controllers;

use Craft;
use craft\helpers\Json;
use craft\helpers\UrlHelper;
use craft\web\Controller;
use craft\web\View;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;
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
        
        $element = ExportElement::findOne(['id' => $elementId]);
        return $this->renderTemplate('exporter/_export/_edit', [
            'export' => $element,
            'elementTypeOptions' => $this->config->getAvailableElementTypes(),
            'step' => $step
        ], View::TEMPLATE_MODE_CP);
    }

    public function actionSave()
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
        Craft::$app->getElements()->saveElement($export);
        $url = UrlHelper::cpUrl("exporter/{$export->id}/2");
        return Craft::$app->getResponse()->getHeaders()->set('HX-Redirect', $url);

    }
}
