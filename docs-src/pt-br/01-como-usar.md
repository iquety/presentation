# Como usar

--page-nav--

As apresentações são fabricadas através da biblioteca `Presentation`.

```php

$engine = new TwigEngine();
$engine->addViewPath(__DIR__ . '/Stubs/TwigOne');

$presentation = new Presentation($engine);
$presentation->render('folder.hello', ['name' => 'Ricardo']));
```

--page-nav--
