<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Twig\Tags;

use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\Expression\ReturnPrimitiveTypeInterface;
use Twig\Node\Expression\Test\TrueTest;
use Twig\Node\Node;
use Twig\TwigTest;

#[YieldReady]
class CanNode extends Node
{
    public function __construct(Node $tests, ?Node $else, int $lineno)
    {
        for ($i = 0, $count = \count($tests); $i < $count; $i += 2) {
            $test = $tests->getNode((string) $i);
            if (!$test instanceof ReturnPrimitiveTypeInterface) {
                $tests->setNode((string) $i, new TrueTest($test, new TwigTest('true'), null, $test->getTemplateLine()));
            }
        }
        $nodes = ['tests' => $tests];
        if (null !== $else) {
            $nodes['else'] = $else;
        }

        parent::__construct($nodes, [], $lineno);
    }

    public function compile(Compiler $compiler): void
    {
        $canNode = $this->getNode('tests')->getNode((string) 0);
        $permission = 'permission-' . $canNode->getAttribute('value');

        $compiler->addDebugInfo($this);
        $compiler
            ->write('if (')
            ->raw("  isset(\$context['$permission']) === true\n")
            ->raw("  && (\n")
            ->raw("    \$context['$permission'] === 'true'")
            ->raw("    || \$context['$permission'] === true")
            ->raw("    || \$context['$permission'] === '1'")
            ->raw("    || \$context['$permission'] === 1")
            ->raw("  )\n")
            ->raw(") {\n")
            ->indent();

        // The node might not exists if the content is empty
        if ($this->getNode('tests')->hasNode((string) 1)) {
            $compiler->subcompile($this->getNode('tests')->getNode((string) 1));
        }

        if ($this->hasNode('else')) {
            $compiler
                ->outdent()
                ->write("} else {\n")
                ->indent()
                ->subcompile($this->getNode('else'))
            ;
        }

        $compiler
            ->outdent()
            ->write("}\n");
    }
}
