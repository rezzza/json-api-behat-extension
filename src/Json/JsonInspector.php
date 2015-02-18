<?php

namespace Rezzza\JsonApiBehatExtension\Json;

use JsonSchema\Validator;
use Symfony\Component\PropertyAccess\PropertyAccess;

class JsonInspector
{
    private $evaluationMode;

    private $propertyAccessor;

    public function __construct($evaluationMode)
    {
        $this->evaluationMode = $evaluationMode;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()->getPropertyAccessor();
    }

    public function evaluate(Json $json, $expression)
    {
        if ($this->evaluationMode === 'javascript') {
            $expression = str_replace('->', '.', $expression);
        }

        try {
            return $json->read($expression, $this->propertyAccessor);
        } catch (\Exception $e) {
            throw new \Exception(sprintf('Failed to evaluate expression "%s"', $expression, 0, $e));
        }
    }

    public function validate(Json $json, JsonSchema $schema)
    {
        return $schema->validate($json, new Validator);
    }
}
