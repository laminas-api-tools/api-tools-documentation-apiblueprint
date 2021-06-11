<?php

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\Documentation\ApiFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

use function sprintf;

class ControllerFactory implements FactoryInterface
{
    /**
     * Create and return a Controller instance.
     *
     * @param string $requestedName
     * @return Controller
     * @throws ServiceNotCreatedException If the ApiFactory service is missing.
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        if (
            ! $container->has(ApiFactory::class)
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
     * @return Controller
     * @throws ServiceNotCreatedException If the ApiFactory service is missing.
     */
    public function createService(ServiceLocatorInterface $controllers)
    {
        $container = $controllers->getServiceLocator() ?: $controllers;
        return $this($container, Controller::class);
    }
}
