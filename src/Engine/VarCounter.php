<?php

namespace Sensorario\Engine;

class VarCounter
{
    public function apply($content, $model)
    {
        $re = '/{{count ([\w\.]{0,})}}/m';

        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);

        foreach (array_unique(array_column($matches, 1)) as $var) {

            // @todo considerare due o piu volte il punto
            // @todo considerare match

            if (strpos($var, '.')) {
                [$entity, $field] = explode('.', $var);

                $subject = $model[$entity][$field];
            }

            if (strpos($var, '.') === false ) {
                if (!isset($model[$var])) {
                    throw new \RuntimeException(
                        sprintf('Oops! Variable "%s" is not defined.', $var)
                    );
                }

                $subject = $model[$var];
            }
            
            $content = str_replace('{{count ' . $var . '}}', count($subject), $content);
        }

        return $content;
    }
}
