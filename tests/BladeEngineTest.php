<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Blade\BladeEngine;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;

class BladeEngineTest extends TestCase
{
    /** @test */
    public function viewNotFound(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('Unable to find template "ops.blade.php"');

        $engine = new BladeEngine();
        $engine->addViewPath(__DIR__ . '/Stubs/BladeOne');

        $engine->render('ops', []);
    }
    
    /** @test */
    public function renderViewPathException(): void
    {
        $this->expectException(PathException::class);
        $this->expectExceptionMessage('No view path was added.');

        $engine = new BladeEngine();

        $engine->render('folder.hello', []);
    }

    /** @test */
    public function render(): void
    {
        $engine = new BladeEngine();
        $engine->addViewPath(__DIR__ . '/Stubs/BladeOne');
        $engine->addViewPath(__DIR__ . '/Stubs/BladeTwo');
        $engine->setCachePath(__DIR__ . '/Stubs/BladeCache');

        $this->assertSame('Hello, Ricardo!', $engine->render('folder.hello', ['name' => 'Ricardo']));
        $this->assertSame('Bye, Ricardo!', $engine->render('folder.bye', ['name' => 'Ricardo']));
    }
}
