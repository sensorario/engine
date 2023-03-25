<?php

namespace Sensorario\Test\Engine;

use Sensorario\Engine\VarCounter;
use PHPUnit\Framework\TestCase;

class VarCounterTest extends TestCase
{
    private VarCounter $varCounter;

    public function setUp(): void
    {
        $this->varCounter = new VarCounter();
    }

    /** @test */
    public function shouldCountVarItems()
    {
        $code = $this->varCounter->apply('quantity: {{count items}}', ['items'=>[1,2,3]]);
        $this->assertEquals('quantity: 3', $code);
    }
}
