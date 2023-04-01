<?php

namespace Sensorario\Engine\Ui\View;

use Sensorario\Engine\Engine;
use Sensorario\Engine\Finder;
use Sensorario\Engine\Http\Request;
use Sensorario\Engine\PageBuilder;
use Sensorario\Engine\RenderLoops;
use Sensorario\Engine\Ui\EngineElement;
use Sensorario\Engine\VarCounter;
use Sensorario\Engine\VarRender;

class View implements EngineElement
{
    private function __construct(
        private PageBuilder $builder = new PageBuilder(new Finder),
        private VarRender $varRender = new VarRender,
        private RenderLoops $renderLoops = new RenderLoops(),
        private VarCounter $varCounter = new VarCounter(),
        private Request $request = new Request([]),
        private array $config = [],
    ) { }

    public static function createWithConfig(\stdClass $config): EngineElement
    {
        return new View();
    }

    public function render(): string
    {

        // @todo remove duplication
        $content = $this->builder->apply(__DIR__ . '/templates/', 'view');
        $content = $this->varRender->apply($content, $this->config['model']);
        // $content = $this->renderLoops->apply($content, $this->config['model']);
        // $content = $this->varCounter->apply($content, $this->config['model']);

        return $content;
    }

    public static function withEngine(Engine $engine, array $config): EngineElement
    {
        return new View(
            new PageBuilder(new Finder),
            new VarRender(),
            new RenderLoops(),
            new VarCounter(),
            new Request([]),
            $config,
        );
    }
}
