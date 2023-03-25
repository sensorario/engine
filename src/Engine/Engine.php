<?php

namespace Sensorario\Engine;

class Engine
{
    private $templateFolder;

    private $model = [];

    public function __construct(
        private RenderLoops $renderLoops = new RenderLoops,
        private VarRender $varRender = new VarRender,
        private VarCounter $varCounter = new VarCounter,
        private PageBuilder $pageBuilder = new PageBuilder(new Finder),
    ) { }

    public function addVariable(string $name, null|string|array $value = null)
    {
        if ($value === null) {
            throw new Exceptions\EmptyValueException(
                sprintf('Oops! Variable %s cannot contain empty value', $name)
            );
        }

        $this->model[$name] = $value;
    }

    public function setTemplateFolder(string $templateFolder)
    {
        $this->templateFolder = $templateFolder;
    }

    // @todo passare il model come secondo parametro
    public function render(string $template, $model = [], $return = false)
    {
        // @todo se model != []
        // per ogni chiave, ... ->addVariable(<chiave>, <valore>);
        if ($model !== []) {
            foreach ($model as $key => $value) {
                $this->addVariable($key, $value);
            }
        }

        $content = $this->pageBuilder->apply($this->templateFolder, $template, $model);

        // cicli
        $data = $this->model;

        $content = $this->renderLoops->apply($content, $data);
        $content = $this->varRender->apply($content, $data);
        $content = $this->varCounter->apply($content, $data);


        //  {{componente:{"configu":"razione"}}}
       $pattern = '/\{\{([\w]+):(\{.*\})\}\}/s';
       // @todo mettere un bel check sulla validita del json
       $content = preg_replace_callback($pattern, function($matches) {
           $component = $matches[1];
           $config = json_decode($matches[2], true);
           $ui = match($component) {
                'Grid' => new Ui\Grid\Grid(
                    $this->varRender,
                    $this->pageBuilder,
                    $this->renderLoops,
                    $this->varCounter,
                    $config,
                ),
           };
           return $ui->render();
        }, $content);


        if ($return === false) {
            echo $content;
        }

        return $content;
    }

    public function redirectIfNotAuthenticated()
    {
        if (!isset($_SESSION['username'])) {
            http_response_code(403);
            exit;
        }
    }
}
