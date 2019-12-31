<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\ApiBlueprint\Resource;
use PHPUnit_Framework_TestCase as TestCase;

class ResourceTest extends TestCase
{
    public function setUp()
    {
        $baseServiceMock = $this->getMockBuilder('Laminas\ApiTools\Documentation\Service')->getMock();
        $baseServiceMock
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('Mock Service'));
        $baseServiceMock
            ->expects($this->any())
            ->method('getRouteIdentifierName')
            ->will($this->returnValue('Mock parameter'));

        $this->service  = $baseServiceMock;
        $this->resource = new Resource($baseServiceMock, array(), 'blueprint/test', 'entity');
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
            ->willReturn(array());

        $this->resource->getBodyProperties();
    }
}
