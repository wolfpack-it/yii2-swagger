<?php

namespace WolfpackIT\swagger\actions;

use OpenApi\Annotations\OpenApi;
use function OpenApi\scan;
use yii\helpers\Json;

/**
 * Class SwaggerApiAction
 * @package WolfpackIT\swagger\actions
 */
class SwaggerApiAction extends \light\swagger\SwaggerApiAction
{
    /**
     * Key value replacements after converting to json.
     * Replacements will be done like: {key} => value
     *
     * @var array
     */
    public $replacements = [];

    /**
     * Get swagger object
     *
     * @return OpenApi
     */
    protected function getSwagger()
    {
        return scan($this->scanDir, $this->scanOptions);
    }

    public function run()
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