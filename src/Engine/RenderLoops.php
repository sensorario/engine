<?php

namespace Sensorario\Engine;

class RenderLoops
{
    public function __construct(
        private VarRender $varRender = new VarRender,
        private VarCounter $varCounter = new VarCounter,
    ) { }

    public function apply($content, $data)
    {
        $content = preg_replace_callback(
            '/\{% foreach (\w+) as (\w+) %\}(.*?)\{% endforeach %\}/s',
            function ($matches) use ($data) {
                $array_name = $matches[1];
                $item_name = $matches[2];
                $inner_template = $matches[3];
                $rowIdentifier = $data['rowIdentifier'];
                $output = '';
                $partial = $inner_template;
                foreach ($data[$array_name] as $values) {
                    if ($array_name === 'items') {
                        $values['rowIdentifier'] = $values[$rowIdentifier];
                    }

                    $partial = $this->varRender->apply($partial, [ $item_name => $values ]);
                    $partial = $this->varCounter->apply($partial, [ $item_name => $values ]);
                    $output .= $partial;
                    $partial = $inner_template;
                }

                return $output;
            },
            $content
        );

        return $content;
    }
}
