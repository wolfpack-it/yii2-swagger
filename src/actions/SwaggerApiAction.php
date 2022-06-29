<?php

declare(strict_types=1);

namespace WolfpackIT\swagger\actions;

use OpenApi\Annotations\OpenApi;
use yii\helpers\Json;

class SwaggerApiAction extends \light\swagger\SwaggerApiAction
{
    /**
     * Key value replacements after converting to json.
     * Replacements will be done like: {key} => value
     */
    public array $replacements = [];

    public function run(): array
    {
        /** @var OpenApi $specification */
        $specification = parent::run();

        $result = $specification->toJson();

        foreach ($this->replacements as $search => $replace) {
            $result = str_replace("{{$search}}", $replace, $result);
        }

        return Json::decode($result);
    }
}
