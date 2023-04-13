<?php

namespace Sensorario\Engine;

class IfStatement
{
    public function apply($content, $model)
    {
        $re = '/{% if (.*?) %}(.*?){% endif %}/m';
        preg_match_all($re, $content, $statements, PREG_SET_ORDER, 0);

        if ($statements === []) {
            return $content;
        }

        foreach ($statements as $statement) {
            $re = '/{% if (.*?) %}(.*?){% endif %}/m';
            preg_match_all($re, $statement[0], $matches, PREG_SET_ORDER, 0);

            if(count(explode(' ', $matches[0][1])) === 1) {
                $content = str_replace(
                    '{% if '.$matches[0][1].' %}'.$matches[0][2].'{% endif %}',
                    $model[$matches[0][1]] === true ? $matches[0][2] : '',
                    $content
                );
            }

            if(count(explode(' ', $matches[0][1])) === 3) {
                [$condition, $operand, $value] = explode(' ', $matches[0][1]);
                if ($operand != 'is') {
                    throw new \RuntimeException('Oops! Unknown operand');
                }
                [$key1, $key2] = explode('.', $condition);
                $with = $model[$key1][$key2] == $value ? $matches[0][2] : '';
                $content = str_replace(
                    '{% if '.$key1.'.'.$key2.' is '.$value.' %}'.$matches[0][2].'{% endif %}',
                    $with,
                    $content
                );
            }
        }

        return $content;
    }
}
