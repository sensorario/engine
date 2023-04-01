# Engine

## Components

 - [Engine](/src/Engine/) - template engine
 - [Grid](/src/Engine/Ui/Grid) - a grid
 - [View](/src/Engine/Ui/View) - search items

## Installation

```
composer install sensorario/engine
```

### Template engine

*index.php*

```
require __DIR__ . '/vendor/autoload.php';
use Sensorario\Engine\EngineFactory;
$engine = (new EngineFactory)->getEngine();
$engine->render('prova', [
    'items' => [
        ['id' => 42],
        ['id' => 43],
    ]
]);
```

*prova.daduda.html*

```
<ul>
    {% foreach items as item %}
    <li>{{item.id}}</li>
    {% endforeach %}
</ul>
```

output
```
<ul>
    <li>42</li>
    <li>43</li>
</ul>
```
