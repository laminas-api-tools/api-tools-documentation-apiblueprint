<?php

/**
 * @codingStandardsIgnoreStart Generic.Files.LineLength.TooLong
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 * @codingStandardsIgnoreEnd Generic.Files.LineLength.TooLong
 */

namespace LaminasTest\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\ApiBlueprint\Action;
use Laminas\ApiTools\Documentation\Operation;
use PHPUnit\Framework\TestCase;

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
