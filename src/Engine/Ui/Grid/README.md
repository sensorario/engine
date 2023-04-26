# Grid

## Sintassi

```
{{Grid:{
    "model":{
        "rowIdentifier":"id",
        "title": " ... ",
        "description": " ... ",
        "headers": { ... },
    },
    "source": { ... }
}}}Grid
```

Nel json devono essere definite due variabli: model e source. Source indica il nome della tabella, gli elementi per pagine e orderBy se si vuole ordinare per il valore di una certa colonna.

## Examples

### Il template della griglia

```
<div class="container">
    <h1>{{title}}</h1>
    <p>{{description}}</p>
    {% include pagination %}
    <div class="table">
        <div class="row">
            {% foreach headers as header %}
            <div class="cell">{{header.name}}</div>
            {% endforeach %}
        </div>
        {% foreach items as item %}
        {% explode fields %}
        {% endforeach %}
    </div>
    {% include pagination %}
</div>
```

### La paginazione

```
<div class="centered">
    <div class="navigate">
        pagina {{currentPage}} di {{numOfPages}}. {{numOfRecords}} items -
        <a href="?p=0">inizio</a>
        <a href="?p={{previousPage}}">indietro</a>
        <a href="?p={{nextPage}}">avanti</a>
        <a href="?p={{numOfPages}}">fine</a>
    </div>
</div>
```

### Identificatore di riga

Ogni riga puo' avere una cella con una checkbox. Quando questa viene selezionata, viene applicata una classe al `<div class="row"></div>` che contiene tutte le celle. Questo div deve avere un campo che lo identifichi univocamente. Per far si che qualsiasi insieme di dati possa avere un identificatore di riga configurabile, deve essere indicato con la chiave rowIdentifier:

```
{{Grid:{
    "model":{
        "rowIdentifier":"id",
        "title": " ... ",
        "description": " ... ",
        "headers": { ... },
    },
    "source": { ... }
}}}Grid
```

### Le colonne

Ci sono fondamentalmente due campi: "text" oppure "selection". Il primo mostra semplicemente il contenuto della cella. Se si vuole la cella puo anche rendere linkabile il testo. Se la pagina che stiamo visualizzando e' `/resource` allora il link sarà `/resource/<id>`. In alternativa con la chiave "linking" si puo indicare il campo che verra usato per creare il link: con "linking":"ean" il link verrà costruito cosiç `/resource/<ean>`. Nel secondo caso, invece, verrà messa una checkbox. Con un javascript di esempio sarà possibile anche rendere selezionabili singolarmente o totalmente le celle visibili. Il campo name indica quello che verrà visualizzato come titolo della colonna.

> since version v1.1
> non viene piu indicata la tabella ma una classe che verra poi istanziata direttamente nel nostro dominio

```
{{Grid:{
    "model":{
        "rowIdentifier":"id",
        "title": "Titolo",
        "description": "Descrizione",
        "headers": {
            {"type":"text","field":"id","name":"id"},
            {"type":"text","field":"id","name":"id", "linked":"true"},
            { "type": "text", "field":"ean", "name": "ean", "linked":"true", "linking":"variations_id" },
            {"type":"selection","selection":"id","name":"id"}
        },
    },
    "source": {
        "repository": "Your.App.Repository",
        "itemPerPage": "11"
    }
}}}Grid
```

La configurazione viene passata completamente al PageBuilder.

```php
namespace Sensorario\Engine\Ui\Grid;

class Grid
{
    public function __construct(
        private VarRender $varRender = new VarRender,
        private PageBuilder $builder = new PageBuilder(new Finder),
        private RenderLoops $renderLoops = new RenderLoops(),
        private VarCounter $varCounter = new VarCounter(),
        private array $config = [],
    ) { }

    public function render(): string
    {
        // ...

        $this->builder->preload($this->config);

        // ...

        return $content;
    }
}
```

All'interno del page builder, ... quando si incontrera' {% explode fields %} ci sara una sostituzione. Verrà aggiunta una `<div class="row"></div>` che al suo interno conterra tutte le colonne `<div class="cell">{{item.{$fieldName}}}</div>`.

```php
$re = '/(?s){% explode fields %}/m';
preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
if ($matches != []) {
    $return = '';
    foreach ($this->preload as $fieldName) {
        $return .= "\n\t<div class=\"cell\">{{item.{$fieldName}}}</div>";
    }
    $exploded = <<<ENGINE
    <div class="row">
    $return
    </div>
    ENGINE;
    $content = str_replace('{% explode fields %}', $exploded, $content);
}
```

### Javascript

```javascript
  document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('[data-form="delete"]')
    const datasets = []
    for (const button of deleteButtons) {
      const string = JSON.stringify(button.dataset)
      const json = JSON.parse(string)
      datasets.push(json)
    }
    for (const dataset of datasets) {
      const button = document.querySelector('[data-id="' + dataset.id + '"]')
      button.addEventListener('click', event => {
        console.log(event.target)
        fetch('https://www.example.com/tasks/' + dataset.id, {
          method: 'DELETE',
        })
          .then(response => response.json())
          .then(response => console.log(response))
          .then(() => document.location.reload())
      })
    }
  })
```
