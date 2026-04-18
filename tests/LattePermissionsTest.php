<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Latte\LatteEngine;

class LattePermissionsTest extends TestCase
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
        $engine = new LatteEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/LatteOne'], __DIR__ . '/Stubs/LatteCache');

        $data = [];

        // Latte não aceita variáveis slug
        $hiPermission = $this->toSnakeCase($hiPermission);
        $byePermission = $this->toSnakeCase($byePermission);

        if ($hiPermission !== '') {
            $data["permission_$hiPermission"] = $hiValue;
        }

        if ($byePermission !== '') {
            $data["permission_$byePermission"] = $byeValue;
        }

        $this->assertSame(
            $renderedTemplate,
            $engine->render('folder.can_permission', $data, [])
        );

        $this->assertSame(
            $this->toHtml($renderedTemplate),
            $engine->render('folder.html_can_permission', $data, [])
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
        $engine = new LatteEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/LatteOne'], __DIR__ . '/Stubs/LatteCache');

        $data = [];

        // Latte não aceita variáveis slug
        $hiPermission = $this->toSnakeCase($hiPermission);
        $byePermission = $this->toSnakeCase($byePermission);

        if ($hiPermission !== '') {
            $data["permission_$hiPermission"] = $hiValue;
        }

        if ($byePermission !== '') {
            $data["permission_$byePermission"] = $byeValue;
        }

        $this->assertSame(
            $renderedTemplate,
            $engine->render('folder.cannot_permission', $data, [])
        );

        $this->assertSame(
            $this->toHtml($renderedTemplate),
            $engine->render('folder.html_cannot_permission', $data, [])
        );
    }

    private function toSnakeCase(string $slugCase): string
    {
        return str_replace('-', '_', $slugCase);
    }

    private function toHtml(string $expected): string
    {
        if ($expected === ' and ') {
            return $expected;
        }

        if (str_starts_with($expected, ' and') === true) {
            return str_replace(' and ', ' and <div>', $expected) . '</div>';
        }

        if (str_ends_with($expected, 'and ') === true) {
            return '<div>' . str_replace(' and ', '</div> and ', $expected);
        }

        return '<div>' . str_replace(' and ', '</div> and <div>', $expected) . '</div>';
    }
}
