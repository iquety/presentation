<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Latte\Tags;

use Latte\CompileException;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\NodeTraverser;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

/**
 * <div n:can="my-permission"> ... <div n:cannotelse>
 */
final class NCannotelseNode extends StatementNode
{
    public AreaNode $content;

    public ?ExpressionNode $condition = null;

    /** @return \Generator<int, ?list<string>, array{AreaNode, ?Tag}, static> */
    public static function create(Tag $tag): \Generator
    {
        $node = $tag->node = new static;

        [$node->content] = yield;

        return $node;
    }

    public function print(PrintContext $context): string
    {
        throw new \LogicException('Cannot directly print');
    }

    public function &getIterator(): \Generator
    {
        if ($this->condition) {
            yield $this->condition;
        }
        yield $this->content;
    }

    public static function processPass(Node $node): void
    {
        (new NodeTraverser)->traverse($node, function (Node $node) {
            if ($node instanceof Nodes\FragmentNode) {
                $node->children = self::processFragment($node->children);
            } elseif ($node instanceof self) {
                self::processFragment([$node]);
            }
        });
    }

    /**
     * @param  AreaNode[]  $children
     * @return AreaNode[]
     */
    private static function processFragment(array $children): array
    {
        $currentNode = null;
        for ($i = 0; isset($children[$i]); $i++) {
            $child = $children[$i];

            if ($child instanceof CannotNode) {
                $currentNode = $child;

            } elseif ($child instanceof Nodes\TextNode && trim($child->content) === '') {
                continue;

            } elseif ($child instanceof self) {
                $nElse = $child;
                if ($currentNode === null) {
                    throw new CompileException('n:cannotelse must be immediately after n:cannot', $nElse->position);
                } elseif ($currentNode->else) {
                    throw new CompileException('Multiple "cannotelse" found.', $nElse->position);
                }

                if ($nElse->condition) {
                    $elseIfNode = new CannotNode;
                    $elseIfNode->condition = $nElse->condition;
                    $elseIfNode->then = $nElse->content;
                    $elseIfNode->position = $nElse->position;
                    $currentNode->else = $elseIfNode;
                    $currentNode = $elseIfNode;
                } else {
                    $currentNode->else = $nElse->content;
                    $currentNode = null;
                }

                unset($children[$i]);
                for ($o = 1; ($children[$i - $o] ?? null) instanceof Nodes\TextNode; $o++) {
                    unset($children[$i - $o]);
                }
            } else {
                $currentNode = null;
            }
        }

        return array_values($children);
    }
}
