<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\Api as BaseApi;

class Api
{
    /** @var BaseApi */
    private $api;

    /** @var ResourceGroup[] */
    private $resourceGroups = [];

    public function __construct(BaseApi $api)
    {
        $this->api = $api;
        $this->createResourceGroups();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->api->getName();
    }

    /**
     * @return ResourceGroup[]
     */
    public function getResourceGroups()
    {
        return $this->resourceGroups;
    }

    /**
     * Iterate API services and create internal resource groups.
     *
     * @return void
     */
    private function createResourceGroups()
    {
        foreach ($this->api->getServices() as $service) {
            $this->resourceGroups[] = new ResourceGroup($service);
        }
    }
}
