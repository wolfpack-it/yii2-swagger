# Swagger for Yii2, extension of light/yii2-swagger

This extension provides [Swagger](https://swagger.io/) actions for the Yii2 Framework.

It is an extension of [light/yii2-swagger](https://packagist.org/packages/light/yii2-swagger).

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require wolfpack-it/yii2-swagger
```

or add

```
"wolfpack-it/yii2-swagger": "^<latest version>"
```

to the `require` section of your `composer.json` file.

If Swagger PHP version 3 or higher is desired, also install:

```html
"zircote/swagger-php": "<latest version> as 2.0.0",
```

## Usage

Use the package by extending the `\WolfpackIT\swagger\controllers\SwaggerController` in the application where you need it.
Override the init function to set the scan directories:
```php
public function init()
{
    $this->scanDirs = [
         \Yii::getAlias('@api/models'),
         \Yii::getAlias('@api/controllers'),
    ];
    parent::init;
}
```

You need to add the following to the application params:

```php
    'swagger' => [
        'oAuthConfiguration' => [
            'baseUrl' => 'https://example.com/oauth/', // must end with a / (forward slash)
            'securityScheme' => 'exampleSecurity', // will be used to auto login
            'username' => 'info@example.nl', // will be used to auto login
            'password' => 'example', // will be used to auto login
            'clientId' => 'example-client', // will be used to auto login
            'clientSecret' => 'example', // will be used to auto login
        ]
    ]
```

It is advised to only use the auto login functionality when the application is not in production!

If you need more granular control, look at how the actions are configured in `\WolfpackIT\swagger\controllers\SwaggerController`.

The rest of the documentation can be found at [light/yii2-swagger](https://packagist.org/packages/light/yii2-swagger).

## Credits
- [Joey Claessen](https://github.com/joester89)
- [Wolfpack IT](https://github.com/wolfpack-it)

## License

The MIT License (MIT). Please see [LICENSE](https://github.com/wolfpack-it/yii2-swagger/blob/master/LICENSE) for more information.