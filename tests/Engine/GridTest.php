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

class FakeClass implements Repository
{
    public function findPaginated(int $itemPerPage = 10): array
    {
        return [];
    }

    public function count(): int
    {
        return 42;
    }

    public function setWhereCondition(): void
    {
    }

    public function setWhereNotInCondition(): void
    {
    }
}

class GridTest extends TestCase
{
    private Engine $engine;

    public function setUp(): void
    {
        $this->engine = new Engine(
            new RenderLoops(),
            new VarRender(),
            new VarCounter(),
            new PageBuilder(),
        ); // $this->getMockBuilder(Engine::class)->getMock();
    }

    /** @test */
    public function shouldThrowExceptionWheneverRowIdentifierIsMissing()
    {
        $this->expectException(MissingRowIdentifierException::class);
        $this->expectExceptionMessage('Oops! Missing model.rowIdentifier');

        $grid = Grid::withEngine($this->engine, []);

        $grid->render();
    }

    /** @test */
    public function shouldRenderPagination()
    {
        $grid = Grid::withEngine(
            $this->engine,
            [
                'model' => [
                    'title' => 'titolo',
                    'description' => 'descrizione',
                    'rowIdentifier' => 'id',
                    'headers' => [],
                ],
                'source' => [
                    'itemPerPage' => 10,
                    'repository' => 'Sensorario.Tests.Engine.FakeClass',
                ],
            ]
        );

        $output = $grid->render();

        $this->assertXpath($output, '//div[@class="navigate"][1]/a[1]', 'inizio');
        $this->assertXpath($output, '//div[@class="navigate"][1]/a[2]', 'indietro');
        $this->assertXpath($output, '//div[@class="navigate"][1]/a[3]', 'avanti');
        $this->assertXpath($output, '//div[@class="navigate"][1]/a[4]', 'fine');
        $this->assertXpath($output, '//div[@class="navigate"][1]/div[1]', '(( 10 items per pagina // 42 record totali ))');
        $this->assertXpath($output, '//div[@class="navigate"][1]/span[1]', '| pagina 1 di 5 |');
    }

    private function assertXpath($content, $search, $expected)
    {
        $html = '<div class="root-element">'.$content.'</div>';
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query($search);
        $this->assertTrue($nodes->length > 0);
        $this->assertEquals($expected, (string) $nodes->item(0)->nodeValue);
    }

    /** @test */
    public function shouldRe()
    {
        $grid = Grid::withEngine(
            $this->engine,
            [
                'model' => [
                    'title' => 'titolo',
                    'description' => 'descrizione',
                    'rowIdentifier' => 'id',
                    'headers' => [
                        [ 'type' => 'sadf', 'name' => 'mario'  ]
                    ],
                ],
                'source' => [
                    'resource' => 'foo',
                    'itemPerPage' => 10,
                    'repository' => 'Sensorario.Tests.Engine.FakeClass',
                ],
            ]
        );

        $output = $grid->render();

        $this->assertXpath($output, '//div[@class="cell header"][1]', 'mario');
    }
}

// aggiungere le custom polocies senza campi aggiuntivi
// .env nel vscode enos ci ha fatto vedere come fare le variabili, prod, stage
