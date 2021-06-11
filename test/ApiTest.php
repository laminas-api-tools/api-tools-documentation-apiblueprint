<?php

/**
 * @codingStandardsIgnoreStart Generic.Files.LineLength.TooLong
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 * @codingStandardsIgnoreEnd Generic.Files.LineLength.TooLong
 */

namespace LaminasTest\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\Api as BaseApi;
use Laminas\ApiTools\Documentation\ApiBlueprint\Api;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    protected function setUp()
    {
        $baseApiMock = $this->getMockBuilder(BaseApi::class)->getMock();
        $baseApiMock->expects($this->once())->method('getServices')->will($this->returnValue([]));
        $baseApiMock->expects($this->any())->method('getName')->will($this->returnValue('Mock API'));
        $this->api = new Api($baseApiMock);
    }

    public function testApiName()
    {
        $this->assertEquals($this->api->getName(), 'Mock API');
    }

    public function testResourceGroups()
    {
        $this->assertEquals($this->api->getResourceGroups(), []);
    }
}
