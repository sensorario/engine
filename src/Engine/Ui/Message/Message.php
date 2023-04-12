<?php

namespace Sensorario\Engine\Ui\Message;

use Sensorario\Engine\Engine;
use Sensorario\Engine\Ui\EngineElement;
use stdClass;

class Message implements EngineElement
{
    private function __construct(
        private \stdClass $config = new stdClass(),
    ) {

    }

    public static function createWithConfig(\stdClass $config): EngineElement
    {
        return new Message($config);
    }

    public static function withEngine(Engine $engine, array $config): EngineElement
    {
        $obj = new stdClass();
        $obj->element = $config;
        return new Message($obj);
    }

    public function render(): string
    {
        return 'Missing tool: ' . var_export($this->config->element, true);
    }
}
