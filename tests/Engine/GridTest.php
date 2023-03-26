<?php

namespace Sensorario\Tests\Engine;

use PHPUnit\Framework\TestCase;
use Sensorario\Engine\Ui\Grid\Grid;
use Sensorario\Engine\Ui\Grid\Exceptions\MissingRowIdentifierException;

class GridTest extends TestCase
{
    /** @test */
    public function shouldThrowExceptionWheneverRowIdentifierIsMissing()
    {
        $this->expectException(MissingRowIdentifierException::class);
        $this->expectExceptionMessage('Oops! Missing row identifier');

        $grid = new Grid;
        $grid->render();
    }
}

/**
 * valutare se effettivamente non si possa evitare di assegnarlo ad ogni record, ...
 *      forse basta passarlo una volta sola, .. no? e non stare a modificare
 *      tutte le righe per aggiungere un valore che tanto non cambia mai, ...
 */