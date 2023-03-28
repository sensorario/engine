<?php

namespace Sensorario\Engine\Ui\Grid;

use Sensorario\Engine\Connection\Connection;
use Sensorario\Engine\Finder;
use Sensorario\Engine\PageBuilder;
use Sensorario\Engine\RenderLoops;
use Sensorario\Engine\VarCounter;
use Sensorario\Engine\VarRender;

class Grid
{
    public function __construct(
        private Repository $repo,
        private VarRender $varRender = new VarRender,
        private PageBuilder $builder = new PageBuilder(new Finder),
        private RenderLoops $renderLoops = new RenderLoops(),
        private VarCounter $varCounter = new VarCounter(),
        private array $config = [],
    ) { }

    public function render(): string
    {
        if (!isset($this->config['model']['rowIdentifier'])) {
            throw new Exceptions\MissingRowIdentifierException(
                sprintf('Oops! Missing row identifier')
            );
        }

        // @todo introduce Request Object
        $query = [];
        $query['p'] = (int) $_GET['p'];

        // upgrade source with current page
        $this->config['source']['currentPage'] = $query['p'];

        // update model
        $this->config['model']['nextPage'] = $query['p'] + 1;
        $this->config['model']['previousPage'] = $query['p'] - 1;
        $this->config['model']['currentPage'] = $query['p'];
        $this->config['model']['items'] = $this->repo->findPaginated();
        $this->config['model']['numOfRecords'] = $this->repo->count();
        $this->config['model']['numOfPages'] = (int) (
            $this->config['model']['numOfRecords'] /
            $this->config['source']['itemPerPage']
        );

        $this->builder->preload($this->config);

        $content = $this->builder->apply(__DIR__ . '/templates/', 'grid');
        $content = $this->renderLoops->apply($content, $this->config['model']);
        $content = $this->varRender->apply($content, $this->config['model']);
        $content = $this->varCounter->apply($content, $this->config['model']);

        return $content;
    }
}
