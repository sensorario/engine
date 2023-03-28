<?php

namespace Sensorario\Engine\Ui\Grid;

interface Repository
{
    public function findPaginated(int $itemPerPage = 10): array;

    public function count(int $itemPerPage = 10): int;

    public function setWhereCondition(): void;

    public function setWhereNotInCondition(): void;
}
