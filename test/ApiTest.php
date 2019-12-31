<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\ApiBlueprint\Api;
use PHPUnit_Framework_TestCase as TestCase;

class ApiTest extends TestCase
{
    public function setUp()
    {
        $baseApiMock = $this->getMockBuilder('Laminas\ApiTools\Documentation\Api')->getMock();
        $baseApiMock->expects($this->once())->method('getServices')->will($this->returnValue(array()));
        $baseApiMock->expects($this->any())->method('getName')->will($this->returnValue('Mock API'));
        $this->api = new Api($baseApiMock);
    }

    public function testApiName()
    {
        $this->assertEquals($this->api->getName(), 'Mock API');
    }

    public function testResourceGroups()
    {
        $this->assertEquals($this->api->getResourceGroups(), array());
    }
}
