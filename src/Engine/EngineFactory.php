<?php

namespace Sensorario\Engine;

use Sensorario\Engine\Finder;
use Sensorario\Engine\PageBuilder;
use Sensorario\Engine\RenderLoops;
use Sensorario\Engine\VarCounter;
use Sensorario\Engine\VarRender;

class EngineFactory
{
    public function __construct()
    {
        
    }

    public function getEngine()
    {
        return new Engine(
            new RenderLoops(),
            new VarRender(
                catchMissingVariable: false,
            ),
            new VarCounter,
            new PageBuilder(
                new Finder,
            ),
        );
    }
}
