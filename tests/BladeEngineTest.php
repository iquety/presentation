<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\EngineException;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;
use Iquety\Presentation\Engine\Blade\BladeEngine;

class BladeEngineTest extends TestCase
{
    /** @test */
    public function engineException(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('The engine was not booted.');

        $engine = new BladeEngine();

        $engine->render('folder.hello', [], []);
    }

    /** @test */
    public function pathException(): void
    {
        $this->expectException(PathException::class);
        $this->expectExceptionMessage('No template paths were specified.');

        $engine = new BladeEngine();
        $engine->bootEngine([], '');

        $engine->render('folder.hello', [], []);
    }

    /** @test */
    public function viewException(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('Unable to find template "ops.blade.php"');

        $engine = new BladeEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/BladeOne'], '');

        $engine->render('ops', [], []);
    }

    /** @test */
    public function render(): void
    {
        $viewPathList = [__DIR__ . '/Stubs/BladeOne', __DIR__ . '/Stubs/BladeTwo'];
        $cachePath    = __DIR__ . '/Stubs/BladeCache';

        $engine = new BladeEngine();
        $engine->bootEngine($viewPathList, $cachePath);

        $data        = ['name' => 'Fulano'];
        $defaultData = ['lastName' => 'de Tal'];

        $this->assertSame('Hello, Fulano de Tal!', $engine->render('folder.hello', $data, $defaultData));
        $this->assertSame('Bye, Fulano de Tal!', $engine->render('folder.bye', $data, $defaultData));

        // efd011oc12d--b45d2dbe1358a6f6eeb99.bladec
        // 8d011c124db--45d2dbe1358a6f6eeb5c0.bladec
        $listFiles = $this->listFiles($cachePath);

        foreach ($listFiles as $file) {
            $permission = fileperms($file);
            $permissionOctal = decoct($permission);
            $permissionQuad = substr($permissionOctal, -4);

            $this->assertEquals('0644', $permissionQuad, "The file $file does not have permission 0644.");
        }
    }
}
