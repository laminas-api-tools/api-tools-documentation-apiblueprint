<?php

declare(strict_types=1);

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

    public function testActionDescription(): void
    {
        self::assertEquals('Mock Operation Description', $this->action->getDescription());
    }

    public function testActionHttpMethod(): void
    {
        self::assertEquals('POST', $this->action->getHttpMethod());
    }

    public function testAllowsChangingEntityMethod(): void
    {
        self::assertTrue($this->action->allowsChangingEntity());
    }

    public function testActionRequestDescription(): void
    {
        self::assertEquals('Mock request description', $this->action->getRequestDescription());
    }

    public function testActionResponseDescription(): void
    {
        self::assertEquals('Mock response description', $this->action->getResponseDescription());
    }

    /** @param mixed $returnValue */
    private function setExpectation(MockObject $baseOperationMock, string $methodName, $returnValue): void
    {
        $baseOperationMock->expects($this->any())->method($methodName)->will($this->returnValue($returnValue));
    }
}
