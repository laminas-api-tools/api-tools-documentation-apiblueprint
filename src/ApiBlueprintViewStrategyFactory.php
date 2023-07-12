<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Psr\Container\ContainerInterface;

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
