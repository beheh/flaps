<?php

namespace BehEh\Flaps\Violation;

use PHPUnit\Framework\TestCase;

class HttpViolationHandlerTest extends TestCase
{

    /**
     * @var HttpViolationHandler
     */
    protected $handler;

    public function setUp()
    {
        $this->handler = $this->getMockBuilder('\BehEh\Flaps\Violation\HttpViolationHandler')
                ->setMethods(array('sendHeader', 'callExit'))
                ->getMock();
    }

    /**
     * @covers BehEh\Flaps\Violation\HttpViolationHandler::handleViolation
     */
    public function testHandleViolation()
    {
        $this->handler->expects($this->once())->method('sendHeader');
        $this->handler->expects($this->once())->method('callExit');
        $this->handler->handleViolation();
    }

}
