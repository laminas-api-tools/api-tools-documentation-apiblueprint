<?php

/**
 * @codingStandardsIgnoreStart Generic.Files.LineLength.TooLong
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 * @codingStandardsIgnoreEnd Generic.Files.LineLength.TooLong
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
        return new ApiBlueprintViewStrategy($container->get(ApiBlueprintRenderer::class));
    }
}
