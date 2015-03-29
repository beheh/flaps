<?php

namespace BehEh\Flaps;

use BehEh\Flaps\Mock\Storage as MockStorage;
use BehEh\Flaps\Mock\ViolationHandler as MockViolationHandler;

class FlapsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Flaps
     */
    protected $flaps;

    public function setUp()
    {
        $this->flaps = new Flaps(new MockStorage());
    }

    /**
     * @covers BehEh\Flaps\Flaps::setDefaultViolationHandler
     * @covers BehEh\Flaps\Flaps::getFlap
     * @covers BehEh\Flaps\Flaps::__get
     */
    public function testDefaultViolationHandler()
    {
        $handler = new MockViolationHandler();
        $this->flaps->setDefaultViolationHandler($handler);
        $this->assertInstanceOf('BehEh\Flaps\Flap', $this->flaps->getFlap('default'));
        $this->assertInstanceOf('BehEh\Flaps\Flap', $this->flaps->default);
        $this->assertSame($handler, $this->flaps->getFlap('default')->getViolationHandler());
    }

}
