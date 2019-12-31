<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\Operation as BaseOperation;
use Laminas\ApiTools\Documentation\Service as BaseService;

class Resource
{

    const RESOURCE_TYPE_ENTITY = 'entity';
    const RESOURCE_TYPE_COLLECTION = 'collection';
    const RESOURCE_TYPE_RPC = 'rpc';

    /**
     * @var BaseOperation[]
     */
    private $operations;

    /**
     * @var BaseService[]
     */
    private $service;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var Action[]
     */
    private $actions = [];

    /**
     * @var self::RESOURCE_TYPE_*
     */
    private $resourceType;

    /**
     * @param BaseService $service
     * @param BaseOperation[] $operations
     * @param string $uri
     * @param string $resourceType self::RESOURCE_TYPE_*
     */
    public function __construct(BaseService $service, array $operations, $uri, $resourceType)
    {
        $this->service = $service;
        $this->operations = $operations;
        $this->uri = $uri;
        $this->createActions();
        $this->resourceType = $resourceType;

        if ($this->getResourceType() == self::RESOURCE_TYPE_COLLECTION && $this->getParameter()) {
            $this->uri .= "?page={page}";
        }

    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->service->getName();
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return string self::RESOURCE_TYPE_*
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }

    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->service->getRouteIdentifierName();
    }

    /**
     * @return \Laminas\ApiTools\Documentation\Field[]
     */
    public function getBodyProperties()
    {
        return $this->service->getFields('input_filter');
    }

    /**
     * @return Action[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    private function createActions()
    {
        foreach ($this->operations as $operation) {
            $action = new Action($operation);
            if ($action->allowsChangingEntity()) {
                $action->setBodyProperties(array_filter($this->service->getFields('input_filter'), function ($field) {
                    return $field->isRequired();
                }));
            }
            $this->actions[] = $action;
        }
    }
}
