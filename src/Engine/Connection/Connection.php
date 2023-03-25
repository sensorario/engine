<?php

namespace Sensorario\Engine\Connection;

use \Pdo;

interface Connection
{
    public function connect(): void;

    public function getPdo(): Pdo;
}
