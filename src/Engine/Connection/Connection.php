<?php

namespace Sensorario\Engine\Connection;

interface Connection
{
    public function connect(): void;

    public function getPdo(): Pdo;
}
