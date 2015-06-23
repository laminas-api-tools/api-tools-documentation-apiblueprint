<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZFTest\Apigility\Documentation\ApiBlueprint;

use PHPUnit_Framework_TestCase as TestCase;
use ZF\Apigility\Documentation\ApiBlueprint\Api;

class ApiTest extends TestCase
{
    public function setUp()
    {
        $baseApiMock = $this->getMockBuilder('ZF\Apigility\Documentation\Api')->getMock();
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
