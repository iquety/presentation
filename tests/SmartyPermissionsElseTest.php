<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Smarty\SmartyEngine;

class SmartyPermissionsElseTest extends TestCase
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
            $engine->render('folder.cannot_else_permission', $data, [])
        );
    }
}
