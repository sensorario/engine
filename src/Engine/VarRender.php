<?php

namespace Sensorario\Engine;

class VarRender
{
    public function __construct(
        private bool $catchMissingVariable = true
    ) {
    }

    public function apply($content, $model)
    {
        $re = '/{{([\w\.]+)}}/m';

        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);

        foreach (array_unique(array_column($matches, 1)) as $var) {
            $keys = explode('.', $var);
            $value = $model;

            foreach ($keys as $key) {
                if (!isset($value[$key]) && $this->catchMissingVariable === true) {
                    throw new Exceptions\MissingVariableException(
                        sprintf('<pre>Oops! Variable "%s" is not defined. Content id %s. Defined variables are <br /> <br /><pre>%s</pre>. <br /> <br />', $key, $content, var_export($model, true))
                    );
                }
                $value = $value[$key] ?? null;
            }

            if (is_array($value)) {
                $value = count($value);
            }

            $content = str_replace('{{' . $var . '}}', $value ?? '', $content);
        }
        return $content;
    }
}
