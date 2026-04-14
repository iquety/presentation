<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Twig\TwigEngine;

class TwigEngineCannotTest extends TestCase
{
    /** @return array<string,array<mixed>> */
    public function cannotPermissionsProvider(): array
    {
        $list = [];

        $list['bool hi']              = ['show-hi', false, 'show-bye', true, "I can say hi and I can't say bye"];
        $list['bool bye']             = ['show-hi', true, 'show-bye', false, "I can't say hi and I can say bye"];
        $list['bool hi and bool bye'] = ['show-hi', false, 'show-bye', false, 'I can say hi and I can say bye'];
        $list['false both']           = ['show-hi', true, 'show-bye', true, "I can't say hi and I can't say bye"];

        $list['string hi']                = ['show-hi', 'false', 'show-bye', 'true', "I can say hi and I can't say bye"];
        $list['string bye']               = ['show-hi', 'true', 'show-bye', 'false', "I can't say hi and I can say bye"];
        $list['string hi and string bye'] = ['show-hi', 'false', 'show-bye', 'false', 'I can say hi and I can say bye'];
        $list['string false both']        = ['show-hi', 'true', 'show-bye', 'true', "I can't say hi and I can't say bye"];

        $list['int hi']             = ['show-hi', 0, 'show-bye', 1, "I can say hi and I can't say bye"];
        $list['int bye']            = ['show-hi', 1, 'show-bye', 0, "I can't say hi and I can say bye"];
        $list['int hi and int bye'] = ['show-hi', 0, 'show-bye', 0, 'I can say hi and I can say bye'];
        $list['int false both']     = ['show-hi', 1, 'show-bye', 1, "I can't say hi and I can't say bye"];

        $list['numeric hi']                 = ['show-hi', '0', 'show-bye', '1', "I can say hi and I can't say bye"];
        $list['numeric bye']                = ['show-hi', '1', 'show-bye', '0', "I can't say hi and I can say bye"];
        $list['numeric hi and numeric bye'] = ['show-hi', '0', 'show-bye', '0', 'I can say hi and I can say bye'];
        $list['numeric false both']         = ['show-hi', '1', 'show-bye', '1', "I can't say hi and I can't say bye"];

        $list['without permissions'] = ['', '', '', '', 'I can say hi and I can say bye'];

        return $list;
    }

    /**
     * @test
     * @dataProvider cannotPermissionsProvider
     */
    public function cannotPermission(
        string $hiPermission,
        mixed $hiValue,
        string $byePermission,
        mixed $byeValue,
        string $renderedTemplate
    ): void {
        $engine = new TwigEngine();
        $engine->bootEngine([__DIR__ . '/Stubs/TwigOne'], '');

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
