<?php

namespace LaminasTest\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\ApiBlueprint\ApiBlueprintModel;
use Laminas\ApiTools\Documentation\ApiBlueprint\ApiBlueprintRenderer;
use Laminas\ApiTools\Documentation\ApiBlueprint\ApiBlueprintViewStrategy;
use Laminas\EventManager\EventManager;
use Laminas\EventManager\Test\EventListenerIntrospectionTrait;
use Laminas\Http\Request as HttpRequest;
use Laminas\Http\Response as HttpResponse;
use Laminas\Stdlib\Response as StdlibResponse;
use Laminas\View\ViewEvent;
use PHPUnit\Framework\TestCase;

use function method_exists;

/**
 * Test the ApiBlueprintViewStrategy.
 *
 * Based on Swagger view strategy test:
 *
 * - https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/test/SwaggerViewStrategyTest.php
 */
class ApiBlueprintViewStrategyTest extends TestCase
{
    use EventListenerIntrospectionTrait;

    protected function setUp(): void
    {
        $this->events   = new EventManager();
        $this->renderer = new ApiBlueprintRenderer();
        $this->strategy = new ApiBlueprintViewStrategy($this->renderer);
        $this->strategy->attach($this->events);
    }

    public function testStrategyAttachesToViewEventsAtPriority200()
    {
        $this->assertListenerAtPriority(
            [$this->strategy, 'selectRenderer'],
            200,
            ViewEvent::EVENT_RENDERER,
            $this->events
        );

        $this->assertListenerAtPriority(
            [$this->strategy, 'injectResponse'],
            200,
            ViewEvent::EVENT_RESPONSE,
            $this->events
        );
    }

    public function testSelectRendererReturnsApiBlueprintRendererWhenApiBlueprintViewModelIsPresentInEvent(): ViewEvent
    {
        $event = new ViewEvent();
        $event->setName(ViewEvent::EVENT_RENDERER);
        $event->setModel(new ApiBlueprintModel([]));
        $event->setRequest(new HttpRequest());

        $renderer = $this->strategy->selectRenderer($event);
        $this->assertSame($this->renderer, $renderer);
        return $event;
    }

    public function testSelectRendererReturnsNullWhenApiBlueprintViewModelIsNotPresentInEvent(): ViewEvent
    {
        $event = new ViewEvent();
        $event->setName(ViewEvent::EVENT_RENDERER);

        $this->assertNull($this->strategy->selectRenderer($event));
        return $event;
    }

    /**
     * @depends testSelectRendererReturnsApiBlueprintRendererWhenApiBlueprintViewModelIsPresentInEvent
     */
    public function testInjectResponseSetsContentTypeWhenJsonRendererWasSelectedBySelectRendererEvent(
        ViewEvent $event
    ): void {
        $response = new HttpResponse();
        $event->setResponse($response);
        $this->strategy->selectRenderer($event);
        $this->strategy->injectResponse($event);
        $headers = $response->getHeaders();
        $this->assertTrue($headers->has('Content-Type'), 'No Content-Type header in HTTP response!');
        $header = $headers->get('Content-Type');
        $this->assertStringContainsString('text/vnd.apiblueprint+markdown', $header->getFieldValue());
    }

    /**
     * @depends testSelectRendererReturnsNullWhenApiBlueprintViewModelIsNotPresentInEvent
     */
    public function testInjectResponseDoesNothingWhenJsonRendererWasNotSelectedBySelectRendererEvent(
        ViewEvent $event
    ): void {
        $response = new HttpResponse();
        $event->setResponse($response);
        $this->strategy->selectRenderer($event);
        $this->strategy->injectResponse($event);
        $headers = $response->getHeaders();
        $this->assertFalse($headers->has('Content-Type'), 'No Content-Type header in HTTP response!');
    }

    /**
     * @depends testSelectRendererReturnsApiBlueprintRendererWhenApiBlueprintViewModelIsPresentInEvent
     */
    public function testInjectResponseDoesNothingIfResponseIsNotHttpEnabled(ViewEvent $event): void
    {
        $response = new StdlibResponse();
        $event->setResponse($response);
        $this->strategy->selectRenderer($event);
        $this->strategy->injectResponse($event);
        $this->assertFalse(method_exists($response, 'getHeaders'));
    }
}
