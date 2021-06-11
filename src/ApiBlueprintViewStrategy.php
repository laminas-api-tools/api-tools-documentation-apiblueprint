<?php

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\View\ViewEvent;

use function method_exists;

class ApiBlueprintViewStrategy implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /** @var ViewModel */
    protected $model;

    /** @var ApiBlueprintRenderer */
    protected $renderer;

    public function __construct(ApiBlueprintRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events, $priority = 200)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RENDERER, [$this, 'selectRenderer'], $priority);
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, [$this, 'injectResponse'], $priority);
    }

    /**
     * @return null|JsonRenderer
     */
    public function selectRenderer(ViewEvent $e)
    {
        $model = $e->getModel();
        if (! $model instanceof ApiBlueprintModel) {
            return;
        }
        $this->renderer->setRequestUri($e->getRequest()->getUri());
        $this->model = $model;
        return $this->renderer;
    }

    public function injectResponse(ViewEvent $e)
    {
        if (! $this->model instanceof ApiBlueprintModel) {
            return;
        }

        $response = $e->getResponse();
        if (! method_exists($response, 'getHeaders')) {
            return;
        }

        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/vnd.apiblueprint+markdown');
        $response->setContent($e->getResult());
    }
}
