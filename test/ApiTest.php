<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZFTest\Apigility\Documentation\ApiBlueprint;

use PHPUnit\Framework\TestCase;
use ZF\Apigility\Documentation\Api as BaseApi;
use ZF\Apigility\Documentation\ApiBlueprint\Api;

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
