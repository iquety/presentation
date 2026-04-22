<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Blade\BladeEngine;

class BladePermissionsElseTest extends TestCase
{
    use HasPermissionProvidersTrait;
    use HasBladeFixSpaces;

    /**
     * @test
     * @dataProvider canElseProvider
     */
    public function canElse(
        string $hiPermission,
        mixed $hiValue,
        string $byePermission,
        mixed $byeValue,
        string $renderedTemplate
    ): void {
        $engine = new BladeEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/BladeOne'], __DIR__ . '/Stubs/BladeCache');

        $data = [];

        if ($hiPermission !== '') {
            $data["permission_$hiPermission"] = $hiValue;
        }

        if ($byePermission !== '') {
            $data["permission_$byePermission"] = $byeValue;
        }

        $this->assertSame(
            $renderedTemplate,
            $this->fixSpaces($engine->render('folder.can_else_permission', $data, []))
        );
    }

    /**
     * @test
     * @dataProvider cannotElseProvider
     */
    public function cannotElse(
        string $hiPermission,
        mixed $hiValue,
        string $byePermission,
        mixed $byeValue,
        string $renderedTemplate
    ): void {
        $engine = new BladeEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/BladeOne'], __DIR__ . '/Stubs/BladeCache');

        $data = [];

        if ($hiPermission !== '') {
            $data["permission_$hiPermission"] = $hiValue;
        }

        if ($byePermission !== '') {
            $data["permission_$byePermission"] = $byeValue;
        }

        $this->assertSame(
            $renderedTemplate,
            $this->fixSpaces($engine->render('folder.cannot_else_permission', $data, []))
        );
    }
}
