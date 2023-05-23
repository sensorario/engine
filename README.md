# Engine

This is a php engine. Made just to play. Learn. And becouse I simply love code. Please do not use this in production. It works. It is tested. But I am using it just to make some videos about open source or just for training.

[TOC]

## Components

 - [Engine](/src/Engine/) - the engine
 - [Form](/src/Engine/Ui/Form) - forms
 - [Grid](/src/Engine/Ui/Grid) - grid
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

## UI

This engine provides few UI items. A Grid, paginated. A Form.

### View

```
{{View:{
    "model": {
        "title":"Ricerca"
    }
}}}View
```
    
### Form

```
{{Form:{
    "form": {
        "method": "POST",
        "action": "cippalippa"
    },
    "fields": [
        { "name" : "name" },
        { "name" : "surname" },
        { "name" : "ciaone" },
        { "name" : "dob" }
    ]
}}}Form
```
    
### Grid

``` 
{{Grid:{
    "source":{
        "repository":"Sensorario.Engine.ExampleRepo",
        "resource":"",
        "itemPerPage":"2"
    },
    "model":{
        "title":"Griglia",
        "headers": [
            { "type" : "text" , "field" : "name", "name" : "NOME" },
            { "type" : "text" , "field" : "surname", "name" : "COGNOME" }
        ],
        "rowIdentifier":"id"
    }
}}}Grid
```