<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Latte\LatteEngine;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;

class LatteEngineTest extends TestCase
{
    /** @test */
    public function viewNotFound(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('Unable to find template "ops.latte"');

        $engine = new LatteEngine();
        $engine->addViewPath(__DIR__ . '/Stubs/LatteOne');

        $engine->render('ops', []);
    }

    /** @test */
    public function renderViewPathException(): void
    {
        $this->expectException(PathException::class);
        $this->expectExceptionMessage('No view path was added.');

        $engine = new LatteEngine();

        $engine->render('hello', []);
    }

    /** @test */
    public function render(): void
    {
        $engine = new LatteEngine();
        $engine->addViewPath(__DIR__ . '/Stubs/LatteOne');
        $engine->addViewPath(__DIR__ . '/Stubs/LatteTwo');
        $engine->setCachePath(__DIR__ . '/Stubs/LatteCache');

        $this->assertSame('Hello, Ricardo!', $engine->render('folder.hello', ['name' => 'Ricardo']));
        $this->assertSame('Bye, Ricardo!', $engine->render('folder.bye', ['name' => 'Ricardo']));
    }
}
