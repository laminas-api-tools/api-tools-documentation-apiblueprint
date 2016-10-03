<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZF\Apigility\Documentation\ApiBlueprint;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZF\Apigility\Documentation\ApiFactory;

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
        if (! $container->has(ApiFactory::class)) {
            throw new ServiceNotCreatedException(sprintf(
                '%s requires the service %s, which was not found',
                Controller::class,
                ApiFactory::class
            ));
        }

        return new Controller($container->get(ApiFactory::class));
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
