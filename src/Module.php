<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

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
     * @param \Laminas\Mvc\MvcEvent
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
     * @param \Laminas\Mvc\MvcEvent
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
