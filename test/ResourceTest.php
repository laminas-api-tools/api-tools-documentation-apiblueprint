<?php

declare(strict_types=1);

namespace LaminasTest\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\ApiBlueprint\Resource;
use Laminas\ApiTools\Documentation\Service;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    /** @var Service|MockObject */
    private $service;

    /** @var Resource */
    private $resource;

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

    public function testResourceName(): void
    {
        self::assertEquals('Mock Service', $this->resource->getName());
    }

    public function testResourceParameter(): void
    {
        self::assertEquals('Mock parameter', $this->resource->getParameter());
    }

    /**
     * @group 4
     */
    public function testPullsFieldsWhenRetrievingBodyProperties(): void
    {
        $this->service
            ->expects($this->once())
            ->method('getFields')
            ->with($this->equalTo('input_filter'))
            ->willReturn([]);

        $this->resource->getBodyProperties();
    }
}
