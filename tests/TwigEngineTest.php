<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Twig\TwigEngine;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;

class TwigEngineTest extends TestCase
{
    /** @test */
    public function viewNotFound(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('Unable to find template "ops.twig"');

        $engine = new TwigEngine();
        $engine->addViewPath(__DIR__ . '/Stubs/TwigOne');

        $engine->render('ops', []);
    }

    /** @test */
    public function renderException(): void
    {
        $this->expectException(PathException::class);
        $this->expectExceptionMessage('No view path was added.');

        $engine = new TwigEngine();

        $engine->render('folder.hello', []);
    }

    /** @test */
    public function render(): void
    {
        $engine = new TwigEngine();
        $engine->addViewPath(__DIR__ . '/Stubs/TwigOne');
        $engine->addViewPath(__DIR__ . '/Stubs/TwigTwo');
        $engine->setCachePath(__DIR__ . '/Stubs/TwigCache');

        $this->assertSame('Hello, Ricardo!', $engine->render('folder.hello', ['name' => 'Ricardo']));
        $this->assertSame('Bye, Ricardo!', $engine->render('folder.bye', ['name' => 'Ricardo']));
    }
}
