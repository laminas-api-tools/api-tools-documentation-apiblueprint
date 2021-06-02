<?php

namespace LaminasTest\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\ApiBlueprint\Resource;
use Laminas\ApiTools\Documentation\Service;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    protected function setUp(): void
    {
        $baseServiceMock = $this->getMockBuilder(Service::class)->getMock();
        $baseServiceMock
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('Mock Service'));
        $baseServiceMock
            ->expects($this->any())
            ->method('getRouteIdentifierName')
            ->will($this->returnValue('Mock parameter'));

        $this->service  = $baseServiceMock;
        $this->resource = new Resource($baseServiceMock, [], 'blueprint/test', 'entity');
    }

    public function testResourceName()
    {
        $this->assertEquals($this->resource->getName(), 'Mock Service');
    }

    public function testResourceParameter()
    {
        $this->assertEquals($this->resource->getParameter(), 'Mock parameter');
    }

    /**
     * @group 4
     */
    public function testPullsFieldsWhenRetrievingBodyProperties()
    {
        $this->service
            ->expects($this->once())
            ->method('getFields')
            ->with($this->equalTo('input_filter'))
            ->willReturn([]);

        $this->resource->getBodyProperties();
    }
}
