<?php

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\Field;
use Laminas\ApiTools\Documentation\Operation as BaseOperation;
use Laminas\ApiTools\Documentation\Service as BaseService;

use function array_filter;

class Resource
{
    public const RESOURCE_TYPE_ENTITY     = 'entity';
    public const RESOURCE_TYPE_COLLECTION = 'collection';
    public const RESOURCE_TYPE_RPC        = 'rpc';

    /** @var BaseOperation[] */
    private $operations;

    /** @var BaseService[] */
    private $service;

    /** @var string */
    private $uri;

    /** @var Action[] */
    private $actions = [];

    /** @var self::RESOURCE_TYPE_* */
    private $resourceType;

    /**
     * @param BaseOperation[] $operations
     * @param string $uri
     * @param string $resourceType self::RESOURCE_TYPE_*
     */
    public function __construct(BaseService $service, array $operations, $uri, $resourceType)
    {
        $this->service    = $service;
        $this->operations = $operations;
        $this->uri        = $uri;
        $this->createActions();
        $this->resourceType = $resourceType;

        if ($this->getResourceType() === self::RESOURCE_TYPE_COLLECTION && $this->getParameter()) {
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
     * @return Field[]
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

    /**
     * Iterate operations to create actions.
     *
     * @return void
     */
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
