<?php

namespace LaminasTest\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\ApiBlueprint\Action;
use Laminas\ApiTools\Documentation\Operation;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    /** @var Action */
    private $action;

    protected function setUp(): void
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

    public function testAllowsChangingEntityMethod()
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

    /** @param mixed $returnValue */
    private function setExpectation(MockObject $baseOperationMock, string $methodName, $returnValue): void
    {
        $baseOperationMock->expects($this->any())->method($methodName)->will($this->returnValue($returnValue));
    }
}
