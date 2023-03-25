# Engine

Da un lato ho reinventato la ruota. Esistono infatti tanti template engine in php. Ci sto pero' aggiungendo il necessario per poter creare elementi dell'interfaccia custom come le griglie. Sono paginate e consentono in pochissimo di ottenere una griglia completa.

## Esempi

### Variabili mancanti

Se una variabile non e' stata passata nel model si scatena un'eccezione. Per skipparla, bisogna impostare catchMissingVariable a false. In questo modo la variabile non passata non sara cagata manco per niente.

```
use Daduda\Engine;

$engine = new Engine\Engine(
    new Engine\RenderLoops,
    new Engine\VarRender(
        catchMissingVariable: true,
    ),
    new Engine\VarCounter,
    new Engine\PageBuilder,
);
```
