<?php

namespace BehEh\Flaps\Violation;

class PassiveViolationHandlerTest extends \PHPUnit_Framework_TestCase
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
