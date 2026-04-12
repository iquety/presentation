<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Presentation;

class PresentationTest extends TestCase
{
    /** @test */
    public function render(): void
    {
        $engine = $this->createStub(TemplateEngine::class);
        $engine->method('render')->willReturn('Hello World');

        $presentation = new Presentation($engine);

        $this->assertSame('Hello World', $presentation->render('hello-world', []));
    }
}
