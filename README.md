# Engine

## Components

 - [Engine](/src/Engine/) - template engine
 - [Grid](/src/Engine/Ui/Grid) - a grid
 - [View](/src/Engine/Ui/View) - search items

## Installation

```
composer install sensorario/engine
```

### Usage

```
require __DIR__ . '/vendor/autoload.php';
use Sensorario\Engine\EngineFactory;
$engine = (new EngineFactory)->getEngine();
$engine->render('template', $model);
```

### Foreach

```
<ul>
    {% foreach items as item %}
    <li>{{item.id}}</li>
    {% endforeach %}
</ul>
```

### If statement

```
{% if foo.bar is 42 %}

{% endif %}
```