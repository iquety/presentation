# Como usar

[◂ Índice da documentação](indice.md) | [Evoluindo a biblioteca ▸](99-evoluindo.md)
-- | --

As apresentações são fabricadas através da biblioteca `Presentation`.

```php

$engine = new TwigEngine();
$engine->addViewPath(__DIR__ . '/Stubs/TwigOne');

$presentation = new Presentation($engine);
$presentation->render('folder.hello', ['name' => 'Ricardo']));
```

[◂ Índice da documentação](indice.md) | [Evoluindo a biblioteca ▸](99-evoluindo.md)
-- | --
