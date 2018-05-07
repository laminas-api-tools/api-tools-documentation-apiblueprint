<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZFTest\Apigility\Documentation\ApiBlueprint;

use PHPUnit\Framework\TestCase;
use ZF\Apigility\Documentation\ApiBlueprint\Action;
use ZF\Apigility\Documentation\Operation;

class ActionTest extends TestCase
{
    protected function setUp()
    {
        $baseOperationMock = $this->getMockBuilder(Operation::class)->getMock();
        $this->setExpectation($baseOperationMock, 'getDescription', 'Mock Operation Description');
        $this->setExpectation($baseOperationMock, 'getHttpMethod', 'POST');
        $this->setExpectation($baseOperationMock, 'getRequestDescription', 'Mock request description');
        $this->setExpectation($baseOperationMock, 'getResponseDescription', 'Mock response description');
        $this->action = new Action($baseOperationMock);
    }

    public function testActionDescription()
    {
        $this->assertEquals($this->action->getDescription(), 'Mock Operation Description');
    }

    public function testActionHttpMethod()
    {
        $this->assertEquals($this->action->getHttpMethod(), 'POST');
    }

    public function testallowsChangingEntityMethod()
    {
        $this->assertTrue($this->action->allowsChangingEntity());
    }

    public function testActionRequestDescription()
    {
        $this->assertEquals($this->action->getRequestDescription(), 'Mock request description');
    }

    public function testActionResponseDescription()
    {
        $this->assertEquals($this->action->getResponseDescription(), 'Mock response description');
    }

    private function setExpectation($baseOperationMock, $methodName, $returnValue)
    {
        $baseOperationMock->expects($this->any())->method($methodName)->will($this->returnValue($returnValue));
    }
}
