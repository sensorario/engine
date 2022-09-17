<?php

$page = 'page';
$hierarchy = [];
$cache = [];
$blocks = [];

do {
    $hierarchy[] = $page;
    $filename = __DIR__ . '/../templates/'.$page.'.engine';
    $cache[$page] = file_get_contents($filename);
    $re = '/extends \'([\w]{0,})\'/m';
    preg_match_all($re, $cache[$page], $matches, PREG_SET_ORDER, 0);
    if ($matches != []) {
        $page = $matches[0][1];
    }
} while ($matches != []);

foreach(array_reverse($hierarchy) as $template) {
    $re = '/ block ([\w<>\/ \n\s]{0,}) /m';
    $content = $cache[$template];
    preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
    $blockList = array_column($matches, 1);
    foreach ($blockList as $block) {
        $re = '/{% block '.$block.' %}([\{\}\[\]\w=\"\<\/\> \n\.]{0,}){% endblock '.$block.' %}/m';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        if ($matches != []) {
            $blocks[$block] = $matches[0][1];
        }
    }
}

$content = end($cache);
foreach($blocks as $name => $block) {
    $re = '/{% block '.$name.' %}([\{\}\[\]\w=\"\<\/\> \n\.]{0,}){% endblock '.$name.' %}/m';
    $content = preg_replace($re, $blocks[$name], $content);
}

$model = [ 'title' => 'titolo dal modello', ];

$re = '/{{([\w]{0,})}}/m';
preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
foreach(array_unique(array_column($matches, 1)) as $var) {
    $content = str_replace('{{'.$var.'}}', $model[$var], $content);
}

echo $content;
