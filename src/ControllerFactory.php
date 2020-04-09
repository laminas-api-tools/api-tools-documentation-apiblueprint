<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\Documentation\ApiFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ControllerFactory implements FactoryInterface
{
    /**
     * Create and return a Controller instance.
     *
     * @param ContainerInterface $container
     * @return Controller
     * @throws ServiceNotCreatedException if the ApiFactory service is missing.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (! $container->has(ApiFactory::class)
            && ! $container->has(\ZF\Apigility\Documentation\ApiFactory::class)
        ) {
            throw new ServiceNotCreatedException(sprintf(
                '%s requires the service %s, which was not found',
                Controller::class,
                ApiFactory::class
            ));
        }

        $apiFactory = $container->has(ApiFactory::class)
            ? $container->get(ApiFactory::class)
            : $container->get(\ZF\Apigility\Documentation\ApiFactory::class);

        return new Controller($apiFactory);
    }

    /**
     * Create and return a Controller instance (v2).
     *
     * Provided for backwards compatibility; proxies to __invoke().
     *
     * @param ServiceLocatorInterface $controllers
     * @return Controller
     * @throws ServiceNotCreatedException if the ApiFactory service is missing.
     */
    public function createService(ServiceLocatorInterface $controllers)
    {
        $container = $controllers->getServiceLocator() ?: $controllers;
        return $this($container, Controller::class);
    }
}
