<?php

/**
 * @codingStandardsIgnoreStart Generic.Files.LineLength.TooLong
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 * @codingStandardsIgnoreEnd Generic.Files.LineLength.TooLong
 */

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\Api as BaseApi;

class Api
{
    /**
     * @var BaseApi
     */
    private $api;

    /**
     * @var ResourceGroup[]
     */
    private $resourceGroups = [];

    /**
     * @param BaseApi $api
     */
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
