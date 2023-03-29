# Engine

## Piccola presentazione

In poche parole si puo renderizzare una griglia paginata usando questo, diciamo, template.

```
{{Grid:{
    "model":{
        "rowIdentifier":"id",
        "title": "Titolo",
        "description": "Descrizione",
        "headers": {
            {"type":"text","field":"id","name":"id"},
            {"type":"text","field":"id","name":"id", "linked":"true"},
            {"type": "text", "field":"ean", "name": "ean", "linked":"true", "linking":"variations_id"},
            {"type":"selection","selection":"id","name":"id"}
        },
    },
    "source": {
        "repository": "Your.App.Repository",
        "itemPerPage": "11"
    }
}}}
```

## Componenti

 - [Engine](/src/Engine/) - li template engine
 - [Grid](/src/Engine/Ui/Grid) - una griglia paginata

## Installazione

### 1. Ogni griglia vuole il proprio reposotory

```
<?php

namespace Sensorario\Engine\Ui\Grid;

interface Repository
{
    public function findPaginated(int $itemPerPage = 10): array;

    public function count(): int;

    public function setWhereCondition(): void;

    public function setWhereNotInCondition(): void;
}
```

### 2. Si istanzia il template engine

Si lo so non e' il massimo ma per ora e' il massimo che sono riuscito a fare.

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

### 3. Si configura un po

```
$engine->setTemplateFolder('templates/');
$engine->addVariable('website', 'example.com');
```

### 4. Si usa

```
$this->render('nome-tempalte', [ 'variabile' => 'valore', ]);
```
