<?php

namespace Sensorario\Engine;

use Sensorario\Engine\Ui\Grid\Repository;

class ExampleRepo implements Repository
{
    private array $data = [
        [ 'id' => 1, 'name' => 'Simone', 'surname' => 'Gentili', ],
        [ 'id' => 2, 'name' => 'Rocco', 'surname' => 'Siffredi', ],
        [ 'id' => 3, 'name' => 'Maccio', 'surname' => 'Capatonda', ],
        [ 'id' => 4, 'name' => 'Gabriele', 'surname' => 'Bianchi', ],
        [ 'id' => 5, 'name' => 'Giovanni', 'surname' => 'Verdi', ],
        [ 'id' => 6, 'name' => 'Dario', 'surname' => 'Fabbri', ],
    ];

    public function findPaginated(int $itemPerPage = 10): array
    {
        $paginaCorrente = $_GET['p'] ?? 1;
        $offset = $itemPerPage * ($paginaCorrente - 1);

        return array_slice($this->data, $offset, $itemPerPage);
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function setWhereCondition(): void
    {

    }

    public function setWhereNotInCondition(): void
    {

    }
}