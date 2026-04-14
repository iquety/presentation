<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Presentation;
use ReflectionObject;

class PresentationTest extends TestCase
{
    /** @test */
    public function addDefaultData(): void
    {
        $presentation = new Presentation($this->createStub(TemplateEngine::class));

        $reflection = new ReflectionObject($presentation);
        $this->assertEquals([], $reflection->getProperty('defaultData')->getValue($presentation));

        $settedValue = ['foo' => 'bar'];

        $presentation->addDefaultData($settedValue);

        $this->assertEquals($settedValue, $reflection->getProperty('defaultData')->getValue($presentation));
    }

    /** @test */
    public function addViewPath(): void
    {
        $presentation = new Presentation($this->createStub(TemplateEngine::class));

        $reflection = new ReflectionObject($presentation);
        $this->assertEquals([], $reflection->getProperty('templatePathList')->getValue($presentation));

        $settedValue = [__DIR__];
        $presentation->addViewPath($settedValue[0]);

        $this->assertEquals($settedValue, $reflection->getProperty('templatePathList')->getValue($presentation));
    }

    /** @test */
    public function setCachePath(): void
    {
        $presentation = new Presentation($this->createStub(TemplateEngine::class));

        $reflection = new ReflectionObject($presentation);
        $this->assertEquals('', $reflection->getProperty('cachePath')->getValue($presentation));

        $settedValue = 'foo';
        $presentation->setCachePath($settedValue);

        $this->assertEquals($settedValue, $reflection->getProperty('cachePath')->getValue($presentation));
    }

    /** @test */
    public function render(): void
    {
        $engine = $this->createStub(TemplateEngine::class);
        $engine->method('bootEngine')->willReturnSelf();
        $engine->method('render')->willReturn('Hello World');

        $presentation = new Presentation($engine);

        $this->assertSame('Hello World', $presentation->render('hello-world', []));
    }
}
