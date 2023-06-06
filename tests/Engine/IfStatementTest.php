<?php

namespace Sensorario\Tests\Engine;

use DOMDocument;
use DOMXPath;
use PHPUnit\Framework\TestCase;
use Sensorario\Engine\Finder;
use Sensorario\Engine\PageBuilder;
use Sensorario\Engine\RenderLoops;
use Sensorario\Engine\Ui\Grid\Exceptions\MissingRowIdentifierException;
use Sensorario\Engine\Ui\Grid\Grid;
use Sensorario\Engine\Ui\Grid\Repository;
use Sensorario\Engine\VarCounter;
use Sensorario\Engine\VarRender;
use Sensorario\Engine\Engine;
use Sensorario\Engine\IfStatement;

class IfStatementTest extends TestCase
{
    private IfStatement $if;

    public function setUp(): void
    {
        $this->if = new IfStatement();
    }

    /** @test */
    public function neverUpdateEmptyContent()
    {
        $content = $this->if->apply('', []);
        $this->assertEquals('', $content);
    }

    /** @test */
    public function considerValorizedVariableAsTrue()
    {
        $content = $this->if->apply("{% if foo %}ww{% endif %}", ['foo' => 42]);
        $this->assertEquals('ww', $content);
    }

}
