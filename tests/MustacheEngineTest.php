<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\Mustache\MustacheEngine;

class MustacheEngineTest extends TestCase
{
    /** @test */
    public function renderViewPathException(): void
    {
        $this->expectException(PathException::class);
        $this->expectExceptionMessage('No view path was added.');

        $engine = new MustacheEngine();

        $engine->render('hello', []);
    }

    /** @test */
    public function render(): void
    {
        $engine = new MustacheEngine();
        $engine->addViewPath(__DIR__ . '/Stubs/MustacheOne');
        $engine->addViewPath(__DIR__ . '/Stubs/MustacheTwo');
        $engine->setCachePath(__DIR__ . '/Stubs/MustacheCache');

        $this->assertSame('Hello, Ricardo!', $engine->render('folder.hello', ['name' => 'Ricardo']));
        $this->assertSame('Bye, Ricardo!', $engine->render('folder.bye', ['name' => 'Ricardo']));
    }
}
