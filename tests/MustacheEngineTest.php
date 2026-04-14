<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\EngineException;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;
use Iquety\Presentation\Engine\Mustache\MustacheEngine;

class MustacheEngineTest extends TestCase
{
    /** @test */
    public function engineException(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('The engine was not booted.');

        $engine = new MustacheEngine();

        $engine->render('folder.hello', [], []);
    }

    /** @test */
    public function pathException(): void
    {
        $this->expectException(PathException::class);
        $this->expectExceptionMessage('No template paths were specified.');

        $engine = new MustacheEngine();
        $engine->bootEngine([], '');

        $engine->render('folder.hello', [], []);
    }

    /** @test */
    public function viewException(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('Unable to find template "ops.ms"');

        $engine = new MustacheEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/MustacheOne'], '');

        $engine->render('ops', [], []);
    }

    /** @test */
    public function render(): void
    {
        $viewPathList = [__DIR__ . '/Stubs/MustacheOne', __DIR__ . '/Stubs/MustacheTwo'];
        $cachePath    = __DIR__ . '/Stubs/MustacheCache';

        $engine = new MustacheEngine();
        $engine->bootEngine($viewPathList, $cachePath);

        $data        = ['name' => 'Fulano'];
        $defaultData = ['lastName' => 'de Tal'];

        $this->assertSame('Hello, Fulano de Tal!', $engine->render('folder.hello', $data, $defaultData));
        $this->assertSame('Bye, Fulano de Tal!', $engine->render('folder.bye', $data, $defaultData));

        // __Mustache_/efd011oc12db45d2dbe1358a6f6eeb99.php
        // __Mustache_8d011c124db45d2dbe1358a6f6eeb5c0.php
        $listFiles = $this->listFiles($cachePath);

        foreach ($listFiles as $file) {
            $permission = (int) fileperms($file);
            $permissionOctal = decoct($permission);
            $permissionQuad = substr($permissionOctal, -4);

            $this->assertEquals('0644', $permissionQuad, "The file $file does not have permission 0644.");
        }
    }
}
