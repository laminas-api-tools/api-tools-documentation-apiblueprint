<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZF\Apigility\Documentation\ApiBlueprint;

class Module
{
    /**
     * Retrieve module configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Listen to MVC bootstrap event.
     *
     * Attaches module's render listener to MVC event manager instance.
     *
     * @param \Zend\Mvc\MvcEvent
     * @return void
     */
    public function onBootstrap($e)
    {
        $app    = $e->getApplication();
        $events = $app->getEventManager();
        $events->attach('render', [$this, 'onRender'], 100);
    }

    /**
     * Listen to MVC render event.
     *
     * Attaches ApiBlueprintViewStrategy to view event manager instance, if an
     * ApiBlueprint view model is detected.
     *
     * @param \Zend\Mvc\MvcEvent
     * @return void
     */
    public function onRender($e)
    {
        $model = $e->getResult();
        if (! $model instanceof ApiBlueprintModel) {
            return;
        }

        $app      = $e->getApplication();
        $services = $app->getServiceManager();
        $view     = $services->get('View');
        $events   = $view->getEventManager();
        $services->get(ApiBlueprintViewStrategy::class)->attach($events);
    }
}
