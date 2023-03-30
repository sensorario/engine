# Engine

## Piccola presentazione

Provando a fare una sorta di clone di twig, mi sono ritrovato a creare un piccolo componente per una ui. Quindi mi sono ritrovato con un template engine in grado pure di renderizzare una griglia paginata partendo solo da un piccolo json di configurazione. Poi, ... siccome voglio riutilizzarlo in altri miei esperimenti, piu per studio e gioco che altro, mi sono deciso di mettere l'engine anche su packagist in modo da renderlo disponibile a qualunque pazzo abbia voglia di giocarci.

## Componenti

 - [Engine](/src/Engine/) - li template engine
 - [Grid](/src/Engine/Ui/Grid) - una griglia paginata

## Come provarlo

### Installazione

```
composer install sensorario/engine
```

### Cicli

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