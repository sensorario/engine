<?php

namespace Sensorario\Engine\Ui;

use Sensorario\Engine\Engine;

interface EngineElement
{
    public function render(): string;

    public static function createWithConfig(\stdClass $config): EngineElement;

    public static function withEngine(Engine $engine, array $config): EngineElement;
}
