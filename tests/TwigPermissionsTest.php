<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Twig\TwigEngine;

class TwigPermissionsTest extends TestCase
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
        $engine = new TwigEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/TwigOne'], __DIR__ . '/Stubs/TwigCache');

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
        $engine = new TwigEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/TwigOne'], __DIR__ . '/Stubs/TwigCache');

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
