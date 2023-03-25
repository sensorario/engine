<?php

namespace Sensorario\Test\Engine;

use Sensorario\Engine\Engine;
use Sensorario\Engine\Exceptions;
use Sensorario\Engine\Finder;
use Sensorario\Engine\RenderLoops;
use Sensorario\Engine\VarRender;
use Sensorario\Engine\VarCounter;
use Sensorario\Engine\PageBuilder;
use PHPUnit\Framework\TestCase;

class VarRenderTest extends TestCase
{
    private VarRender $varRender;

    private $varRenderSkippingErrors;

    public function setUp(): void
    {
        $this->varRender = new VarRender();

        $this->varRenderSkippingErrors = new VarRender(
            catchMissingVariable: false,
        );
    }

    /** @test */
    public function shouldThrowExceptionWheneverVariableContentIsMissing()
    {
        $this->expectException(Exceptions\MissingVariableException::class);

        $this->varRender->apply('{{varname}}', []);
    }

    /** @test */
    public function shouldThrowExceptionWheneverVariableContentIsNull()
    {
        $this->expectException(Exceptions\MissingVariableException::class);

        $this->varRender->apply('{{varname}}', ['varname' => null]);
    }

    /** @test */
    public function shouldOverwriteVariableWheneverPresentInModel()
    {
        $result = $this->varRender->apply(
            '{{varname}}',
            ['varname' => 'value'],
        );

        $this->assertEquals('value', $result);
    }

    /** @test */
    public function shouldSkipMissingVariableWheneverRequested()
    {
        $result = $this->varRenderSkippingErrors->apply('{{missing}}', []);
        $this->assertEquals('', $result);
    }
}
