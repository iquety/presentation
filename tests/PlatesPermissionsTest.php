<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Plates\PlatesEngine;

class PlatesPermissionsTest extends TestCase
{
    use HasPermissionProvidersTrait;

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
        $engine = new PlatesEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/PlatesOne'], '');

        $data = [];

        if ($hiPermission !== '') {
            $data["permission-$hiPermission"] = $hiValue;
        }

        if ($byePermission !== '') {
            $data["permission-$byePermission"] = $byeValue;
        }

        $this->assertSame(
            $renderedTemplate,
            $engine->render('folder.can_permission', $data, [])
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
        $engine = new PlatesEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/PlatesOne'], '');

        $data = [];

        if ($hiPermission !== '') {
            $data["permission-$hiPermission"] = $hiValue;
        }

        if ($byePermission !== '') {
            $data["permission-$byePermission"] = $byeValue;
        }

        $this->assertSame(
            $renderedTemplate,
            $engine->render('folder.cannot_permission', $data, [])
        );
    }
}
