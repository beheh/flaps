<?php

namespace BehEh\Flaps\Throttling;

use PHPUnit\Framework\TestCase;

class ViolateAlwaysStrategyTest extends TestCase
{

    /**
     * @var ViolateAlwaysStrategy
     */
    protected $strategy;

    protected function setUp()
    {
        $this->strategy = new ViolateAlwaysStrategy;
    }

    /**
     * @covers BehEh\Flaps\Throttling\ViolateAlwaysStrategy::isViolator
     */
    public function testIsViolator()
    {
        $this->assertTrue($this->strategy->isViolator('identifier'));
        $this->assertTrue($this->strategy->isViolator(null));
        $this->assertTrue($this->strategy->isViolator(true));
        $this->assertTrue($this->strategy->isViolator(false));
    }

}
