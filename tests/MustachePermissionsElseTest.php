<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Mustache\MustacheEngine;

class MustachePermissionsElseTest extends TestCase
{
    use HasPermissionProvidersTrait;

    /**
     * @test
     * @dataProvider canElseProvider
     */
    public function canElseTag(
        string $hiPermission,
        mixed $hiValue,
        string $byePermission,
        mixed $byeValue,
        string $renderedTemplate
    ): void {
        $engine = new MustacheEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/MustacheOne'], '');

        $data = [];

        if ($hiPermission !== '') {
            $data["permission-$hiPermission"] = $hiValue;
        }

        if ($byePermission !== '') {
            $data["permission-$byePermission"] = $byeValue;
        }

        $this->assertSame(
            $renderedTemplate,
            $engine->render('folder.can_else_permission', $data, [])
        );
    }

    /**
     * @test
     * @dataProvider cannotElseProvider
     */
    public function cannotElseTag(
        string $hiPermission,
        mixed $hiValue,
        string $byePermission,
        mixed $byeValue,
        string $renderedTemplate
    ): void {
        $engine = new MustacheEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/MustacheOne'], '');

        $data = [];

        if ($hiPermission !== '') {
            $data["permission-$hiPermission"] = $hiValue;
        }

        if ($byePermission !== '') {
            $data["permission-$byePermission"] = $byeValue;
        }

        $this->assertSame(
            $renderedTemplate,
            $engine->render('folder.cannot_else_permission', $data, [])
        );
    }
}
