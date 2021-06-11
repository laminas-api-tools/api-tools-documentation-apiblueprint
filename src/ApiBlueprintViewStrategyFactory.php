<?php

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Interop\Container\ContainerInterface;

class ApiBlueprintViewStrategyFactory
{
    /**
     * @return ApiBlueprintViewStrategy
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ApiBlueprintViewStrategy($container->get(ApiBlueprintRenderer::class));
    }
}
