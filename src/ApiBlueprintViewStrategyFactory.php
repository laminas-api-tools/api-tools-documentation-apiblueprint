<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

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
