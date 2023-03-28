<?php

namespace Sensorario\Engine\Ui\Grid;

interface Repository
{
    public function findPaginated(): array;

    public function count(): int;

    public function setWhereCondition(): void;

    public function setWhereNotInCondition(): void;
}
