<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\Plates\PlatesEngine;
use Iquety\Presentation\Engine\ViewException;

class PlatesEngineTest extends TestCase
{
    /** @test */
    public function viewNotFound(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('Unable to find template "ops.tpl"');

        $engine = new PlatesEngine();
        $engine->addViewPath(__DIR__ . '/Stubs/PlatesOne');

        $engine->render('ops', []);
    }
    
    /** @test */
    public function renderViewPathException(): void
    {
        $this->expectException(PathException::class);
        $this->expectExceptionMessage('No view path was added.');

        $engine = new PlatesEngine();

        $engine->render('hello', []);
    }

    /** @test */
    public function render(): void
    {
        $engine = new PlatesEngine();
        $engine->addViewPath(__DIR__ . '/Stubs/PlatesOne');
        $engine->addViewPath(__DIR__ . '/Stubs/PlatesTwo');
        $engine->setCachePath(__DIR__ . '/Stubs/PlatesCache');

        $this->assertSame('Hello, Ricardo!', $engine->render('folder.hello', ['name' => 'Ricardo']));
        $this->assertSame('Bye, Ricardo!', $engine->render('folder.bye', ['name' => 'Ricardo']));
    }
}
