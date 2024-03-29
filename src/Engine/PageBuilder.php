<?php

namespace Sensorario\Engine;

class PageBuilder
{
    private $preloaded = [];

    public function __construct(
        private Finder $finder = new Finder(),
        private IfStatement $ifStatement = new IfStatement(),
    ) {
    }

    public function preload(array $config)
    {
        $this->preloaded = $config;
    }

    public function apply($templateFolder, $page)
    {
        $hierarchy = [];
        $cache = [];
        $blocks = [];

        // gerarchia template
        do {
            $hierarchy[] = $page;
            $fullPath = $templateFolder . $page . '.daduda.html';
            $filename = realpath($fullPath);
            if ($this->finder->notExists($filename)) {
                throw new Exceptions\MissingTemplateException(
                    sprintf('Oops! Template "%s" (%s) not exists', $page, $fullPath),
                );
            }
            $cache[$page] = $this->finder->getFileContent($filename);
            $re = '/extends \'([\w]{0,})\'/m';
            preg_match_all($re, $cache[$page], $matches, PREG_SET_ORDER, 0);
            if ($matches != []) {
                $page = $matches[0][1];
            }
        } while ($matches != []);

        // gerarchia blocchi
        foreach (array_reverse($hierarchy) as $template) {
            $re = '/ block ([\w<>\/ \n\s]{0,}) /m';
            $content = $cache[$template];
            preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
            $blockList = array_column($matches, 1);
            foreach ($blockList as $block) {
                $re = '/(?s){% block ' . $block . ' %}(.*?){% endblock ' . $block . ' %}/m';
                preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
                if ($matches != []) {
                    $blocks[$block] = $matches[0][1];
                }
            }
        }

        // renderizza blocchi
        $content = end($cache);
        foreach ($blocks as $name => $block) {
            $re = '/(?s){% block ' . $name . ' %}(.*?){% endblock ' . $name . ' %}/m';
            $content = preg_replace($re, $blocks[$name], $content);
        }

        // include
        $re = '/(?s){% include (.*?) %}/m';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        if ($matches != []) {
            $tpl = $matches[0][1];
            $file = realpath($templateFolder . $tpl . '.daduda.html');
            if (!file_exists($file)) {
                throw new \RuntimeException(sprintf('Oops! File "%s" (%s) not exists!!', $file, $tpl));
            }
            $fileContent = file_get_contents($file);
            $content = str_replace('{% include '.$tpl.' %}', $fileContent, $content);
        }

        $content = $this->ifStatement->apply($content, $this->preloaded);

        $re = '/(?s){% explode formfields %}/m';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        if ($matches != []) {
            $return = '';
            foreach ($this->preloaded['fields'] as $field) {
                // @todo la risorsa dovrebbe essere dedotta dalla request/querystring
                $return .= Ui\Form\Input::fromField($field);
            }
            $content = str_replace('{% explode formfields %}', $return, $content);
        }

        // @todo rinominare in grid.fields o qualche cosa del genere
        $re = '/(?s){% explode fields %}/m';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        if ($matches != []) {

            if (!isset($this->preloaded['model']['headers'])) {
                throw new Ui\Grid\Exceptions\MissingHeadersException();
            }

            $return = '';
            foreach ($this->preloaded['model']['headers'] as $field) {
                if (isset($this->preloaded['source']['table'])) {
                    throw new \RuntimeException(sprintf('Oop! source.table must be replaced with source.resource in page "..."!!!'));
                }
                if (!isset($this->preloaded['source']['resource'])) {
                    throw new \RuntimeException(sprintf('Oop! source.resource is missing in "'.var_export($this->preloaded['source'], true).'"!!'));
                }
                $resource = $this->preloaded['source']['resource'];
                // @todo la risorsa dovrebbe essere dedotta dalla request/querystring
                $return .= Ui\Grid\Cell::fromField($field, $resource);
            }
            // @todo e se il campo id non e' presente?
            $exploded = <<<ENGINE
            <div class="row" id="id-{{item.rowIdentifier}}">
            $return
            </div>
            ENGINE;
            $content = str_replace('{% explode fields %}', $exploded, $content);
        }

        return $content;
    }
}
