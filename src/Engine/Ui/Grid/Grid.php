<?php

namespace Sensorario\Engine\Ui\Grid;

use Sensorario\Engine\Finder;
use Sensorario\Engine\Http\Request;
use Sensorario\Engine\PageBuilder;
use Sensorario\Engine\RenderLoops;
use Sensorario\Engine\VarCounter;
use Sensorario\Engine\VarRender;

class Grid
{
    private Repository $repo;
    
    public function __construct(
        private PageBuilder $builder = new PageBuilder(new Finder),
        private VarRender $varRender = new VarRender,
        private RenderLoops $renderLoops = new RenderLoops(),
        private VarCounter $varCounter = new VarCounter(),
        private array $config = [],
        private Request $request = new Request([]),
    ) { }

    public function render(): string
    {
        if (!isset($this->config['model']['rowIdentifier'])) {
            throw new Exceptions\MissingRowIdentifierException(
                sprintf('Oops! Missing row identifier')
            );
        }

        // @todo introduce Request Object
        $currentPage = $this->request->get('p', 1);

        // upgrade source with current page
        $this->config['source']['currentPage'] = $query['p'];

        if (!isset($this->config['source'])) {
            throw new \RuntimeException('source is missing');
        }

        if (!isset($this->config['source']['repository'])) {
            throw new \RuntimeException('source.repository is missing');
        }

        $className = str_replace('.', '\\', $this->config['source']['repository']);
        $this->repo = new $className;

        $items = $this->repo->findPaginated(
            itemPerPage: $this->config['source']['itemPerPage'],
        );

        // update model
        $this->config['model']['nextPage'] = $currentPage + 1;
        $this->config['model']['previousPage'] = $currentPage - 1;
        $this->config['model']['currentPage'] = $currentPage;
        $this->config['model']['items'] = $items;
        $this->config['model']['itemPerPage'] = $this->config['source']['itemPerPage'];
        $this->config['model']['numOfRecords'] = $this->repo->count();
        $this->config['model']['numOfPages'] = 1 + (int) (
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
