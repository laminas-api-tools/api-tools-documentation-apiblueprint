<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\Service as BaseService;

class ResourceGroup
{
    /**
     * @var BaseService
     */
    protected $service;

    /**
     * @var Resource[]
     */
    private $resources = [];

    /**
     * @param BaseService $service
     */
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->createResources();
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
    public function getDescription()
    {
        return $this->service->getDescription();
    }

    /**
     * @return Resource[]
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @return string
     */
    private function getCollectionUri()
    {
        return substr($this->service->getRoute(), 0, strpos($this->service->getRoute(), '['));
    }

    /**
     * @return string
     */
    private function getEntityUri()
    {
        return str_replace(['[', ']', '{/', '{:'], ['{', '}', '/{', '{'], $this->service->getRoute());
    }

    private function createResources()
    {
        // If there is routeIdentifierName, it is REST service and we need to
        // handle both collection and entities
        if ($this->service->getRouteIdentifierName()) {
            $this->resources[] = new Resource(
                $this->service,
                $this->service->getOperations(),
                $this->getCollectionUri(),
                Resource::RESOURCE_TYPE_COLLECTION
            );
            $this->resources[] = new Resource(
                $this->service,
                $this->service->getEntityOperations(),
                $this->getEntityUri(),
                Resource::RESOURCE_TYPE_ENTITY
            );
            return;
        }

        $this->resources[] = new Resource(
            $this->service,
            $this->service->getOperations(),
            $this->getEntityUri(),
            Resource::RESOURCE_TYPE_RPC
        );
    }
}
