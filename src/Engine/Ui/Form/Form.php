<?php

namespace Sensorario\Engine\Ui\Form;

use RuntimeException;
use Sensorario\Engine\Engine;
use Sensorario\Engine\Finder;
use Sensorario\Engine\Http\Request;
use Sensorario\Engine\PageBuilder;
use Sensorario\Engine\RenderLoops;
use Sensorario\Engine\Ui\EngineElement;
use Sensorario\Engine\VarCounter;
use Sensorario\Engine\VarRender;

class Form implements EngineElement
{

    private function __construct(
        private PageBuilder $builder = new PageBuilder(new Finder()),
        private VarRender $varRender = new VarRender(),
        private RenderLoops $renderLoops = new RenderLoops(),
        private VarCounter $varCounter = new VarCounter(),
        private Request $request = new Request([]),
        private array $config = [],
    ) {
    }
    public static function createWithConfig(\stdClass $config): EngineElement
    {
        return new Form();
    }

    public static function withEngine(Engine $engine, array $config): EngineElement
    {
        return new Form(
            new PageBuilder(new Finder()),
            $engine->getVariableRender(),
            new RenderLoops(),
            new VarCounter(),
            new Request([]),
            $config,
        );
    }

    public function render(): string
    {
        if (!isset($this->config['fields'])) {
            throw new RuntimeException(
                sprintf('Oops! Missing fields')
            );
        }

        if (!isset($this->config['form'])) {
            throw new RuntimeException(
                sprintf('Oops! Missing form')
            );
        }

        $this->builder->preload($this->config);

        // @todo remove duplication
        $content = $this->builder->apply(__DIR__ . '/templates/', 'form');
        // $content = $this->renderLoops->apply($content, $this->config['model']);
        $content = $this->varRender->apply($content, $this->config['form']);
        // $content = $this->varCounter->apply($content, $this->config['model']);

        return $content;
    }
}