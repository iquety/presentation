# How to use

--page-nav--

Presentations are created using the `Presentation` library.

```php

$engine = new TwigEngine();
$engine->addViewPath(__DIR__ . '/Stubs/TwigOne');

$presentation = new Presentation($engine);
$presentation->render('folder.hello', ['name' => 'Ricardo']));
```

--page-nav--
