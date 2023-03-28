# Engine

Da un lato ho reinventato la ruota. Esistono infatti tanti template engine in php. Ci sto pero' aggiungendo il necessario per poter creare elementi dell'interfaccia custom come le griglie. Sono paginate e consentono in pochissimo di ottenere una griglia completa.

## Esempi

### Variabili mancanti

Se una variabile non e' stata passata nel model si scatena un'eccezione. Per skipparla, bisogna impostare catchMissingVariable a false. In questo modo la variabile non passata non sara cagata manco per niente.

```
use Sensorario\Engine;

$engine = new Engine\Engine(
    new Engine\RenderLoops,
    new Engine\VarRender(
        catchMissingVariable: false,
    ),
    new Engine\VarCounter,
    new Engine\PageBuilder(
        new Engine\Finder,
    ),
);
```

### Cicli

Ed ora supponiamo di avere un certo file chiamato `templates/loop.daduda.html`. E suppoanimo che abbia questo contenuto:

```
{% foreach items as item %}
<li>{{item.id}}</li>
{% endforeach %}
```

La dove si vada ad usare l'engine in questo modo

```
$engine->render('loop', [
    'items' => [
        ['id' => 42],
        ['id' => 43],
    ]
]);
```

Si otterra il seguente codice:

```
<li>42</li>
<li>43</li>
```
