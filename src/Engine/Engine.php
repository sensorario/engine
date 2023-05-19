<?php

namespace Sensorario\Engine;

class Engine
{
    private $templateFolder = '';

    private $model = [];

    public function __construct(
        private RenderLoops $renderLoops = new RenderLoops(),
        private VarRender $varRender = new VarRender(),
        private VarCounter $varCounter = new VarCounter(),
        private PageBuilder $pageBuilder = new PageBuilder(new Finder()),
    ) {
    }

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
        // @todo il nome della cartella deve terminare con '/'
        $this->templateFolder = $templateFolder;
    }

    public function injectConfiguration(array $config)
    {
        $this->setTemplateFolder($config['setTemplateFolder']);
    }

    // @todo passare il model come secondo parametro
    public function render(string $template = 'templates', $model = [], $return = false)
    {
        try {

            if ($this->templateFolder == '') {
                throw new \RuntimeException('Oops! Template folder is not defined');
            }
            // @todo se model != []
            // per ogni chiave, ... ->addVariable(<chiave>, <valore>);
            if ($model !== []) {
                foreach ($model as $key => $value) {
                    $this->addVariable($key, $value);
                }
            }

            $this->pageBuilder->preload($model);
            $content = $this->pageBuilder->apply($this->templateFolder, $template, $model);

            // cicli
            $data = $this->model;

            $content = $this->renderLoops->apply($content, $data);
            $content = $this->varRender->apply($content, $data);
            $content = $this->varCounter->apply($content, $data);

            //  {{componente:{"configu":"razione"}}}
            foreach (['View','Grid'] as $uiElement) {
                $pattern = '/\{\{'.$uiElement.':(.*)\}\}'.$uiElement.'/s';

                // @todo mettere un bel check sulla validita del json
                $content = preg_replace_callback($pattern, function ($matches) use ($template, $uiElement) {
                    $ui = new \stdClass();
                    $ui->element = $matches[0];
                    $ui->conf = $matches[1];

                    $ui = match($uiElement) {
                        'Grid' => Ui\Grid\Grid::withEngine($this, json_decode($ui->conf, true)),
                        'View' => Ui\View\View::withEngine($this, json_decode($ui->conf, true)),
                        default => Ui\Message\Message::createWithConfig($ui),
                    };

                    return $ui->render();

                }, $content);

            }


            if ($return === false) {
                echo $content;
            }

            return $content;
        } catch (\Error|\Exception $e) {
            $class = get_class($e);
            $error = $e->getMessage();
            $templateFolder = $this->templateFolder;
            die(<<<PRE
            <h1>Engine error!!!</h1>
            <pre>
            Class: $class
            Error: $error
            Template: $templateFolder/$template.daduda.html
            </pre>
            PRE);
        }
    }

    public function redirectIfNotAuthenticated()
    {
        if (!isset($_SESSION['username'])) {
            http_response_code(403);
            exit;
        }
    }

    public function getVariableRender()
    {
        return $this->varRender;
    }
}
