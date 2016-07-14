<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZF\Apigility\Documentation\ApiBlueprint;

use Interop\Container\ContainerInterface;

class ApiBlueprintViewStrategyFactory
{
    /**
     * @param ContainerInterface $container
     * @return ApiBlueprintViewStrategy
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ApiBlueprintViewStrategy($container->get(ApiBlueprintViewRenderer::class));
    }
}
