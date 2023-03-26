<?php

namespace Sensorario\Tests\Engine;

use PHPUnit\Framework\TestCase;
use Sensorario\Engine\RenderLoops;

class RenderLoopTest extends TestCase
{
    /** @test */
    public function shouldThrowExceptionWheneverRowIdentifierIsMissing()
    {
        $loops = new RenderLoops;
        $result = $loops->apply(<<<ENGINE
        {% foreach items as item %}
        <li>{{item.id}}</li>
        {% endforeach %}
        ENGINE, [
            'items' => [
                ['id' => 42],
                ['id' => 43],
            ]
        ]);

        $this->assertSame(<<<ENGINE

        <li>42</li>

        <li>43</li>
        
        ENGINE, $result);
    }
}