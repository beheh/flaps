<?php

namespace BehEh\Flaps\Violation;

use PHPUnit\Framework\TestCase;

class PassiveViolationHandlerTest extends TestCase
{

    /**
     * @var PassiveViolationHandler
     */
    protected $handler;

    public function setUp()
    {
        $this->handler = new PassiveViolationHandler;
    }

    /**
     * @covers BehEh\Flaps\Violation\PassiveViolationHandler::handleViolation
     */
    public function testHandleViolation()
    {
        $this->assertFalse($this->handler->handleViolation());
    }

}
