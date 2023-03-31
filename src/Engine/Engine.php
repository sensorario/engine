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
       $content = preg_replace_callback($pattern, function($matches) use ($template) {
            $component = $matches[1];
            $config = json_decode($matches[2], true);
            $grid = new Ui\Grid\Grid(
                $this->pageBuilder,
                $this->varRender,
                $this->renderLoops,
                $this->varCounter,
                $config,
            );
            
            $ui = match($component) {
                    'Grid' => $grid,
            };
            try {
                return $ui->render();

            } catch (\Exception $e) {
                $class = get_class($e);
                $error = $e->getMessage();
                $templateFolder = $this->templateFolder;
                die(<<<PRE
                <h1>Engine error!!!</h1>
                <pre>
                    Class: $class
                    Error: $error
                    Template: /templateFolder/$template.daduda.html
                </pre>
                PRE);
            }

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
