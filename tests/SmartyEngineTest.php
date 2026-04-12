<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\Smarty\SmartyEngine;

class SmartyEngineTest extends TestCase
{
    /** @test */
    public function renderViewPathException(): void
    {
        $this->expectException(PathException::class);
        $this->expectExceptionMessage('No view path was added.');

        $engine = new SmartyEngine();

        $engine->render('hello', []);
    }

    /** @test */
    public function render(): void
    {
        $engine = new SmartyEngine();
        $engine->addViewPath(__DIR__.'/Stubs/SmartyOne');
        $engine->addViewPath(__DIR__.'/Stubs/SmartyTwo');
        $engine->setCachePath(__DIR__.'/Stubs/SmartyCache');

        $this->assertSame('Hello, Ricardo!', $engine->render('folder.hello', ['name' => 'Ricardo']));
        $this->assertSame('Bye, Ricardo!', $engine->render('folder.bye', ['name' => 'Ricardo']));
    }
}

