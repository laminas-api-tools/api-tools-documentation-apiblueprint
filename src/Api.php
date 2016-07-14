<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Inc. <support@apiary.io>
 */

namespace ZF\Apigility\Documentation\ApiBlueprint;

use ZF\Apigility\Documentation\Api as BaseApi;

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
