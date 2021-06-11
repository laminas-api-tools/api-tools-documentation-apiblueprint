<?php

declare(strict_types=1);

namespace LaminasTest\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\Api as BaseApi;
use Laminas\ApiTools\Documentation\ApiBlueprint\Api;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    protected function setUp(): void
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
