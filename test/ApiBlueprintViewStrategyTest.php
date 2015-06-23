<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 * Based on Swagger view strategy test https://github.com/zfcampus/zf-apigility-documentation-swagger/blob/master/test/SwaggerViewStrategyTest.php
 */

namespace ZFTest\Apigility\Documentation\ApiBlueprint;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\EventManager\EventManager;
use Zend\Http\Response as HttpResponse;
use Zend\Http\Request as HttpRequest;
use Zend\Stdlib\Response as StdlibResponse;
use Zend\View\ViewEvent;
use ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintViewStrategy;
use ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintRenderer;
use ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintModel;

class ApiBlueprintViewStrategyTest extends TestCase
{
    public function setUp()
    {
        $this->events   = new EventManager();
        $this->renderer = new ApiBlueprintRenderer();
        $this->strategy = new ApiBlueprintViewStrategy($this->renderer);
        $this->strategy->attach($this->events);
    }

    public function testStrategyAttachesToViewEventsAtPriority200()
    {
        $listeners = $this->events->getListeners(ViewEvent::EVENT_RENDERER);
        $this->assertEquals(1, count($listeners));
        $listener = $listeners->top();
        $this->assertEquals([$this->strategy, 'selectRenderer'], $listener->getCallback());
        $this->assertEquals(200, $listener->getMetadatum('priority'));

        $listeners = $this->events->getListeners(ViewEvent::EVENT_RESPONSE);
        $this->assertEquals(1, count($listeners));
        $listener = $listeners->top();
        $this->assertEquals([$this->strategy, 'injectResponse'], $listener->getCallback());
        $this->assertEquals(200, $listener->getMetadatum('priority'));
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
