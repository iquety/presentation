<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Plates\PlatesEngine;

class PlatesPermissionsElseTest extends TestCase
{
    use HasPermissionProvidersTrait;

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
            $engine->render('folder.can_else_permission', $data, [])
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
            $engine->render('folder.cannot_else_permission', $data, [])
        );
    }
}
