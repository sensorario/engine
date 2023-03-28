# Engine

## Piccola presentazione

Provando a fare una sorta di clone di twig, mi sono ritrovato a creare un piccolo componente per una ui. Quindi mi sono ritrovato con un template engine in grado pure di renderizzare una griglia paginata partendo solo da un piccolo json di configurazione. Poi, ... siccome voglio riutilizzarlo in altri miei esperimenti, piu per studio e gioco che altro, mi sono deciso di mettere l'engine anche su packagist in modo da renderlo disponibile a qualunque pazzo abbia voglia di giocarci.

## Componenti

 - [Engine](/src/Engine/) - li template engine
 - [Grid](/src/Engine/Ui/Grid) - una griglia paginata

## Come provarlo

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
$engine->addVariable('website', 'simonegentili.com');
```

### 4. Si usa

```
$this->render('nome-tempalte', [ 'variabile' => 'valore', ]);
```

## Documentazione

Adesso non ho tanto tempo, .. ma magari nei prossimi giorni ci lavoro. Chissa.
