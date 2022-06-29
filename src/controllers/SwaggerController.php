<?php

declare(strict_types=1);

namespace WolfpackIT\swagger\controllers;

use WolfpackIT\swagger\actions\SwaggerApiAction;
use WolfpackIT\swagger\actions\SwaggerDocAction;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;

class SwaggerController extends Controller
{
    public $defaultAction = 'doc';

    public array $scanDirs = [];

    public function actions()
    {
        return [
            'doc' => [
                'class'=> SwaggerDocAction::class,
                'restUrl' => Url::to(['swagger/api'], true),
                'configurations' => [
                    'oauth2RedirectUrl' => Url::to(['swagger/redirect'], true)
                ],
                'oauthConfiguration' => ArrayHelper::getValue(\Yii::$app->params, 'swagger.oAuthConfiguration', []),
            ],
            'api' => [
                'class' => SwaggerApiAction::class,
                'scanDir' => $this->scanDirs,
                'replacements' => array_filter([
                    'OAUTH_BASE_URL' => ArrayHelper::getValue(\Yii::$app->params, 'swagger.oAuthConfiguration.baseUrl')
                ])
            ]
        ];
    }

    public function actionRedirect()
    {
        return $this->renderPartial('@vendor/swagger-api/swagger-ui/dist/oauth2-redirect.html');
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['api', 'doc', 'redirect']
                        ]
                    ]
                ]
            ],
            parent::behaviors()
        );
    }
}
