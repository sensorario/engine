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
    private Connection $conn;

    public function __construct(
        private VarRender $varRender = new VarRender,
        private PageBuilder $builder = new PageBuilder(new Finder),
        private RenderLoops $renderLoops = new RenderLoops(),
        private VarCounter $varCounter = new VarCounter(),
        private array $config = [],
    ) { }

    public function setConnection()
    {
        $this->conn = new Connection;
    }

    public function render(): string
    {
        $this->conn->connect();
        $pdo = $this->conn->getPdo();

        // @todo introduce Request Object
        $query = [];
        $query['p'] = (int) $_GET['p'];

        // upgrade source with current page
        $this->config['source']['currentPage'] = $query['p'];

        // update model
        $this->config['model']['nextPage'] = $query['p'] + 1;
        $this->config['model']['previousPage'] = $query['p'] - 1;
        $this->config['model']['currentPage'] = $query['p'];

        // @todo move in a new component
        $this->config['model']['items'] = (function($source, $pdo) {
            if ($source['currentPage'] < 0) $source['currentPage'] = 0;
            $sql = sprintf(
                '
                    select *
                    from %s
                    %s
                    limit %s offset %s
                ',
                $source['table'],
                isset($source['orderBy']) ? 'order by ' . $source['orderBy'] . ' desc ' : ' ',
                $source['itemPerPage'],
                $source['currentPage'] * $source['itemPerPage'],
            );
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        })($this->config['source'], $pdo);

        $this->config['model']['numOfRecords'] = (function($source, $pdo) {
            $stmt = $pdo->prepare(sprintf(
                'select count(*) as num from %s',
                $source['table'],
            ));
            $stmt->execute();
            return $stmt->fetch()['num'];
        })($this->config['source'], $pdo);

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
