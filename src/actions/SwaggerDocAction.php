<?php

declare(strict_types=1);

namespace WolfpackIT\swagger\actions;

use light\swagger\SwaggerAction;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\web\Response;

class SwaggerDocAction extends SwaggerAction
{
    public function init(): void
    {
        parent::init();

        $baseUrl = ArrayHelper::getValue($this->oauthConfiguration, 'baseUrl');
        if (substr($baseUrl, -1) !== '/') {
            $baseUrl .= '/';
        }
        $securityScheme = ArrayHelper::getValue($this->oauthConfiguration, 'securityScheme');
        $username = ArrayHelper::getValue($this->oauthConfiguration, 'username');
        $password = ArrayHelper::getValue($this->oauthConfiguration, 'password');
        $clientId = ArrayHelper::getValue($this->oauthConfiguration, 'clientId');
        $clientSecret = ArrayHelper::getValue($this->oauthConfiguration, 'clientSecret');

        if (
            !empty($baseUrl)
            && !empty($securityScheme)
            && !empty($username)
            && !empty($password)
            && !empty($clientId)
            && !empty($clientSecret)
        ) {
            $view = $this->controller->getView();
            $view->registerJs(new JsExpression(<<<JS
fetch(
     '{$baseUrl}access-token',
     {
         method: 'POST',
         mode: 'cors',
         body: $.param({
            grant_type: "password",
            username: "{$username}",
            password: "{$password}",
            client_id: "{$clientId}",
            client_secret: "{$clientSecret}",
         }),
         headers: {
             'Content-Type': 'application/x-www-form-urlencoded',
         }
     }
).then(response => {
    if (response.status === 200) {
        response.json().then(data => {
            window.ui.authActions.preAuthorizeImplicit({
                auth: {
                    schema: {
                        flow: 'password',
                        get: function (key) {
                            return this[key];
                        }
                    },
                    name: '{$securityScheme}'
                },
                token: data,
                isValid: true
            });
        });
    }
}).catch(error => {
    console.error(error)
})
JS
            ));
        }
    }

    public function run(): string
    {
        \Yii::$app->getResponse()->format = Response::FORMAT_HTML;

        $this->controller->layout = false;

        $view = $this->controller->getView();

        return $view->renderFile(__DIR__ . '/../views/index.php', [
            'configurations' => $this->prepareConfiguration(),
            'oauthConfiguration' => $this->oauthConfiguration,
            'title' => $this->title,
        ], $this->controller);
    }
}
