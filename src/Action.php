<?php

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\Field;
use Laminas\ApiTools\Documentation\Operation as BaseOperation;

use function in_array;

class Action
{
    /** @var BaseOperation */
    private $operation;

    /** @var Field[] */
    private $bodyProperties = [];

    /** @var array */
    private static $entityChangingMethods = ['PUT', 'POST', 'PATCH'];

    /**
     * @param BaseOperation $operations
     */
    public function __construct(BaseOperation $operation)
    {
        $this->operation = $operation;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->operation->getDescription();
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->operation->getHttpMethod();
    }

    /**
     * @return array
     */
    public function getPossibleResponses()
    {
        return $this->operation->getResponseStatusCodes();
    }

    /**
     * @return Field[]
     */
    public function getBodyProperties()
    {
        return $this->bodyProperties;
    }

    /**
     * @var Field[] $properties
     */
    public function setBodyProperties(array $properties)
    {
        $this->bodyProperties = $properties;
    }

    /**
     * @return string
     */
    public function getRequestDescription()
    {
        return $this->operation->getRequestDescription();
    }

    /**
     * @return string
     */
    public function getResponseDescription()
    {
        return $this->operation->getResponseDescription();
    }

    /**
     * @return boolean
     */
    public function allowsChangingEntity()
    {
        return in_array($this->getHttpMethod(), self::$entityChangingMethods);
    }
}
