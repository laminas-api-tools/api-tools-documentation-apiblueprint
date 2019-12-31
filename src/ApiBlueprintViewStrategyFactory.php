<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

class ApiBlueprintViewStrategyFactory
{
    /**
     * @param \Laminas\ServiceManager\ServiceLocatorInterface $services
     * @return ApiBlueprintViewStrategy
     */
    public function __invoke($services)
    {
        return new ApiBlueprintViewStrategy($services->get(__NAMESPACE__ . '\ApiBlueprintViewRenderer'));
    }
}
