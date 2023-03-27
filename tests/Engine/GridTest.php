<?php

namespace Sensorario\Tests\Engine;

use PHPUnit\Framework\TestCase;
use Sensorario\Engine\Finder;
use Sensorario\Engine\PageBuilder;
use Sensorario\Engine\RenderLoops;
use Sensorario\Engine\Ui\Grid\Exceptions\MissingRowIdentifierException;
use Sensorario\Engine\Ui\Grid\Grid;
use Sensorario\Engine\Ui\Grid\Repository;
use Sensorario\Engine\VarCounter;
use Sensorario\Engine\VarRender;

class GridTest extends TestCase
{
    private Repository $repo;

    public function setUp(): void
    {
        $this->repo = $this
            ->getMockBuilder(Repository::class)
            ->getMock();
    }

    /** @test */
    public function shouldThrowExceptionWheneverRowIdentifierIsMissing()
    {
        $this->expectException(MissingRowIdentifierException::class);
        $this->expectExceptionMessage('Oops! Missing row identifier');

        $grid = new Grid(
            $this->repo
        );

        $grid->render();
    }

    /** @test */
    public function should()
    {
        $this->repo->expects($this->once())
            ->method('count')
            ->willReturn(123);

        $grid = new Grid(
            $this->repo,
            new VarRender,
            new PageBuilder(new Finder),
            new RenderLoops,
            new VarCounter,
            [
                'model' => [
                    'title' => 'titolo',
                    'description' => 'titolo',
                    'rowIdentifier' => 'id',
                    'headers' => [],
                ],
                'source' => [
                    'itemPerPage' => 10,
                ],
            ]
        );

        $grid->render();
    }
}

/**
 * valutare se effettivamente non si possa evitare di assegnarlo ad ogni record, ...
 *      forse basta passarlo una volta sola, .. no? e non stare a modificare
 *      tutte le righe per aggiungere un valore che tanto non cambia mai, ...
 */
