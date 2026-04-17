<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Smarty\SmartyEngine;

class SmartyPermissionsTest extends TestCase
{
    use HasPermissionProvidersTrait;

    /**
     * @test
     * @dataProvider canProvider
     */
    public function canPermission(
        string $hiPermission,
        mixed $hiValue,
        string $byePermission,
        mixed $byeValue,
        string $renderedTemplate
    ): void {
        $engine = new SmartyEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/SmartyOne'], __DIR__ . '/Stubs/SmartyCache');

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
    public function cannotPermission(
        string $hiPermission,
        mixed $hiValue,
        string $byePermission,
        mixed $byeValue,
        string $renderedTemplate
    ): void {
        $engine = new SmartyEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/SmartyOne'], __DIR__ . '/Stubs/SmartyCache');

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
