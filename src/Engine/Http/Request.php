<?php

namespace Sensorario\Engine\Http;

class Request
{
    public function __construct(private $queryParams)
    {
        $this->queryParams = $_GET;
    }

    public function get($name, $default = null)
    {
        return $this->queryParams[$name] ?? $default;
    }
}
