<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\EngineException;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;
use Iquety\Presentation\Engine\Smarty\SmartyEngine;

class SmartyEngineTest extends TestCase
{
    /** @test */
    public function engineException(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('The engine was not booted.');

        $engine = new SmartyEngine();

        $engine->render('folder.hello', [], []);
    }

    /** @test */
    public function pathException(): void
    {
        $this->expectException(PathException::class);
        $this->expectExceptionMessage('No template paths were specified.');

        $engine = new SmartyEngine();
        $engine->bootEngine([], '');

        $engine->render('folder.hello', [], []);
    }

    /** @test */
    public function viewException(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('Unable to find template "ops.tpl"');

        $engine = new SmartyEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/SmartyOne'], '');

        $engine->render('ops', [], []);
    }

    /** @test */
    public function render(): void
    {
        $viewPathList = [__DIR__ . '/Stubs/SmartyOne', __DIR__ . '/Stubs/SmartyTwo'];
        $cachePath    = __DIR__ . '/Stubs/SmartyCache';

        $engine = new SmartyEngine();
        $engine->bootEngine($viewPathList, $cachePath);

        $data        = ['name' => 'Fulano'];
        $defaultData = ['lastName' => 'de Tal'];

        $this->assertSame('Hello, Fulano de Tal!', $engine->render('folder.hello', $data, $defaultData));
        $this->assertSame('Bye, Fulano de Tal!', $engine->render('folder.bye', $data, $defaultData));

        // cached/efd011oc12db45d2dbe1358a6f6eeb99.tpl.php
        // compiled/8d011c124db45d2dbe1358a6f6eeb5c0.tpl.cache.php
        $listFiles = $this->listFiles($cachePath);

        foreach ($listFiles as $file) {
            $permission = fileperms($file);
            $permissionOctal = decoct($permission);
            $permissionQuad = substr($permissionOctal, -4);

            $this->assertEquals('0644', $permissionQuad, "The file $file does not have permission 0644.");
        }
    }
}
