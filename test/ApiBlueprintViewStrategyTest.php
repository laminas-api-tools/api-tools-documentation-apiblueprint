<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZFTest\Apigility\Documentation\ApiBlueprint;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\EventManager\EventManager;
use Zend\EventManager\Test\EventListenerIntrospectionTrait;
use Zend\Http\Response as HttpResponse;
use Zend\Http\Request as HttpRequest;
use Zend\Stdlib\Response as StdlibResponse;
use Zend\View\ViewEvent;
use ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintViewStrategy;
use ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintRenderer;
use ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintModel;

/**
 * Test the ApiBlueprintViewStrategy.
 *
 * Based on Swagger view strategy test:
 *
 * - https://github.com/zfcampus/zf-apigility-documentation-swagger/blob/master/test/SwaggerViewStrategyTest.php
 */
class ApiBlueprintViewStrategyTest extends TestCase
{
    use EventListenerIntrospectionTrait;

    public function setUp()
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

    public function testSelectRendererReturnsApiBlueprintRendererWhenApiBlueprintViewModelIsPresentInEvent()
    {
        $event = new ViewEvent();
        $event->setName(ViewEvent::EVENT_RENDERER);
        $event->setModel(new ApiBlueprintModel([]));
        $event->setRequest(new HttpRequest());

        $renderer = $this->strategy->selectRenderer($event);
        $this->assertSame($this->renderer, $renderer);
        return $event;
    }

    public function testSelectRendererReturnsNullWhenApiBlueprintViewModelIsNotPresentInEvent()
    {
        $event = new ViewEvent();
        $event->setName(ViewEvent::EVENT_RENDERER);

        $this->assertNull($this->strategy->selectRenderer($event));
        return $event;
    }

    /**
     * @depends testSelectRendererReturnsApiBlueprintRendererWhenApiBlueprintViewModelIsPresentInEvent
     */
    public function testInjectResponseSetsContentTypeWhenJsonRendererWasSelectedBySelectRendererEvent($event)
    {
        $response = new HttpResponse();
        $event->setResponse($response);
        $this->strategy->selectRenderer($event);
        $this->strategy->injectResponse($event);
        $headers = $response->getHeaders();
        $this->assertTrue($headers->has('Content-Type'), 'No Content-Type header in HTTP response!');
        $header = $headers->get('Content-Type');
        $this->assertContains('text/vnd.apiblueprint+markdown', $header->getFieldValue());
    }

    /**
     * @depends testSelectRendererReturnsNullWhenApiBlueprintViewModelIsNotPresentInEvent
     */
    public function testInjectResponseDoesNothingWhenJsonRendererWasNotSelectedBySelectRendererEvent($event)
    {
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
    public function testInjectResponseDoesNothingIfResponseIsNotHttpEnabled($event)
    {
        $response = new StdlibResponse();
        $event->setResponse($response);
        $this->strategy->selectRenderer($event);
        $this->strategy->injectResponse($event);
        $this->assertFalse(method_exists($response, 'getHeaders'));
    }
}
