<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Blade\BladeEngine;

class BladePermissionsTest extends TestCase
{
    use HasPermissionProvidersTrait;
    use HasBladeFixSpaces;

    /**
     * @test
     * @dataProvider canProvider
     */
    public function canTag(
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
            $this->fixSpaces($engine->render('folder.can_permission', $data, []))
        );
    }

    /**
     * @test
     * @dataProvider cannotProvider
     */
    public function cannotTag(
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
            $this->fixSpaces($engine->render('folder.cannot_permission', $data, []))
        );
    }
}
