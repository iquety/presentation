<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\EngineException;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;
use Iquety\Presentation\Engine\Plates\PlatesEngine;

class PlatesEngineTest extends TestCase
{
    /** @test */
    public function engineException(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('The engine was not booted.');

        $engine = new PlatesEngine();

        $engine->render('folder.hello', [], []);
    }

    /** @test */
    public function pathException(): void
    {
        $this->expectException(PathException::class);
        $this->expectExceptionMessage('No template paths were specified.');

        $engine = new PlatesEngine();
        $engine->bootEngine([], '');

        $engine->render('folder.hello', [], []);
    }

    /** @test */
    public function viewException(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('Unable to find template "ops.tpl"');

        $engine = new PlatesEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/PlatesOne'], '');

        $engine->render('ops', [], []);
    }

    /** @test */
    public function render(): void
    {
        $viewPathList = [__DIR__ . '/Stubs/PlatesOne', __DIR__ . '/Stubs/PlatesTwo'];
        $cachePath    = __DIR__ . '/Stubs/PlatesCache';

        $engine = new PlatesEngine();
        $engine->bootEngine($viewPathList, $cachePath);

        $data        = ['name' => 'Fulano'];
        $defaultData = ['lastName' => 'de Tal'];

        $this->assertSame('Hello, Fulano de Tal!', $engine->render('folder.hello', $data, $defaultData));
        $this->assertSame('Bye, Fulano de Tal!', $engine->render('folder.bye', $data, $defaultData));
    }
}
