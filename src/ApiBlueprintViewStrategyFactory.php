<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZF\Apigility\Documentation\ApiBlueprint;

class ApiBlueprintViewStrategyFactory
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return \ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintViewStrategy
     */
    public function __invoke($services)
    {
        return new ApiBlueprintViewStrategy($services->get('ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintViewRenderer'));
    }
}
