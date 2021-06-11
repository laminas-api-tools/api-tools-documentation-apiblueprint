<?php

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\Service as BaseService;

use function str_replace;
use function strpos;
use function substr;

class ResourceGroup
{
    /** @var BaseService */
    protected $service;

    /** @var Resource[] */
    private $resources = [];

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

    /**
     * Create resources from service.
     *
     * If service has a route identifier, creates both entity and collection
     * resources; otherwise, creates an RPC resource.
     *
     * @return void
     */
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
