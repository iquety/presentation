<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Latte\Tags;

use Latte\CompileException;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\Expression\BinaryOpNode;
use Latte\Compiler\Nodes\Php\Expression\IssetNode;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\Scalar\BooleanNode;
use Latte\Compiler\Nodes\Php\Scalar\IntegerNode;
use Latte\Compiler\Nodes\Php\Scalar\StringNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;

/**
 * {can 'my-permission'} ... {canelse} ... {/can}
 * @see vendor/latte/latte/src/Latte/Essential/CoreExtension.php
 * @see vendor/latte/latte/src/Latte/Essential/Nodes/IfNode.php
 */
class CanNode extends StatementNode
{
    public ExpressionNode $condition;
    public AreaNode $then;
    public ?AreaNode $else = null;
    public ?Position $elseLine = null;
    public bool $capture = false;

    /** @return \Generator<int, ?list<string>, array{AreaNode, ?Tag}, static> */
    public static function create(Tag $tag, TemplateParser $parser): \Generator
    {
        $node = $tag->node = new static;

        $node->position = $tag->position;

        $node->capture = !$tag->isNAttribute() && $tag->name === 'can' && $tag->parser->isEnd();

        if ($node->capture === false) {
            $tagCondition = $tag->parser->parseExpression();

            if (!$tagCondition instanceof StringNode) {
                throw new CompileException("Incorrect syntax for can. Use {can 'my-permission'}.", $tag->position);
            }
            
            $canArgument = str_replace(['"', "'", ' '], '', $tag->parser->text);

            $permissionName = 'permission_' . str_replace('-', '_', $canArgument);

            $node->condition = self::makeExpression($permissionName, $node->position);
        }

        [$node->then, $nextTag] = yield ['canelse'];

        if ($nextTag?->name === 'canelse') {
            if ($nextTag->parser->stream->is('canelse')) {
                throw new CompileException('Arguments are not allowed in {canelse}.', $nextTag->position);
            }

            $node->elseLine = $nextTag->position;

            [$node->else, $nextTag] = yield;
        }

        if ($node->capture) {
            assert($nextTag !== null);

            $node->condition = $nextTag->parser->parseExpression();
        }

        return $node;
    }

    private static function makeExpression(string $permissionName, Position $position): BinaryOpNode
    {
        $isset = new BinaryOpNode(
            new IssetNode([new VariableNode($permissionName)]),
            '===',
            new BooleanNode(true)
        );

        $boolString = new BinaryOpNode(
            new VariableNode($permissionName),
            '===',
            new StringNode("true")
        );

        $boolReal = new BinaryOpNode(
            $boolString,
            '||',
            new BinaryOpNode(new VariableNode($permissionName), '===', new BooleanNode(true)
            )
        );

        $numericString = new BinaryOpNode(
            $boolReal,
            '||',
            new BinaryOpNode(new VariableNode($permissionName), '===', new StringNode("1")
            )
        );

        $numericInt = new BinaryOpNode(
            $numericString,
            '||',
            new BinaryOpNode(new VariableNode($permissionName), '===', new IntegerNode(1)
            )
        );

        return new BinaryOpNode($isset, '&&', $numericInt, $position);
    }

    public function print(PrintContext $context): string
    {
        return $this->capture
            ? $this->printCapturing($context)
            : $this->printCommon($context);
    }


    private function printCommon(PrintContext $context): string
    {
        if ($this->else !== null) {
            $text = $context->format(
                ($this->else instanceof self
                    ? "if (%node) %line { %node } else%node\n"
                    : "if (%node) %line { %node } else %4.line { %3.node }\n"),
                $this->condition,
                $this->position,
                $this->then,
                $this->else,
                $this->elseLine,
            );

            return $text;
        }

        $text =  $context->format(
            "if (%node) %line { %node }\n",
            $this->condition,
            $this->position,
            $this->then,
        );

        return $text;
    }


    private function printCapturing(PrintContext $context): string
    {
        if ($this->else) {
            return $context->format(
                <<<'XX'
                    ob_start(fn() => '') %line;
                    try {
                        %node
                        ob_start(fn() => '') %line;
                        try {
                            %node
                        } finally {
                            $ʟ_ifB = ob_get_clean();
                        }
                    } finally {
                        $ʟ_ifA = ob_get_clean();
                    }
                    echo (%node) ? $ʟ_ifA : $ʟ_ifB %0.line;


                    XX,
                $this->position,
                $this->then,
                $this->elseLine,
                $this->else,
                $this->condition,
            );
        }

        return $context->format(
            <<<'XX'
                ob_start(fn() => '') %line;
                try {
                    %node
                } finally {
                    $ʟ_ifA = ob_get_clean();
                }
                if (%node) %0.line { echo $ʟ_ifA; }

                XX,
            $this->position,
            $this->then,
            $this->condition,
        );
    }


    public function &getIterator(): \Generator
    {
        yield $this->condition;
        yield $this->then;
        if ($this->else) {
            yield $this->else;
        }
    }
}
