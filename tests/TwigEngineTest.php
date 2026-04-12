<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Twig\TwigEngine;
use Iquety\Presentation\Engine\PathException;

class TwigEngineTest extends TestCase
{
    /** @test */
    public function renderException(): void
    {
        $this->expectException(PathException::class);
        $this->expectExceptionMessage('No view path was added.');

        $engine = new TwigEngine();

        $engine->render('hello.html', []);
    }

    /** @test */
    public function render(): void
    {
        $engine = new TwigEngine();
        $engine->addViewPath(__DIR__.'/Stubs/TwigOne');
        $engine->addViewPath(__DIR__.'/Stubs/TwigTwo');
        $engine->setCachePath(__DIR__.'/Stubs/TwigCache');

        $this->assertSame('Hello, Ricardo!', $engine->render('folder.hello', ['name' => 'Ricardo']));
        $this->assertSame('Bye, Ricardo!', $engine->render('folder.bye', ['name' => 'Ricardo']));
    }
}

