# How to use

[◂ Documentation index](index.md) | [Evolving the library ▸](99-evolution.md)
-- | --

Presentations are created using the `Presentation` library.

```php

$engine = new TwigEngine();
$engine->addViewPath(__DIR__ . '/Stubs/TwigOne');

$presentation = new Presentation($engine);
$presentation->render('folder.hello', ['name' => 'Ricardo']));
```

[◂ Documentation index](index.md) | [Evolving the library ▸](99-evolution.md)
-- | --
