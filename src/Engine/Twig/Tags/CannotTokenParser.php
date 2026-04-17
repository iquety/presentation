<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Twig\Tags;

use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Node\Nodes;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

final class CannotTokenParser extends AbstractTokenParser
{
    public function parse(Token $token): Node
    {
        $lineno = $token->getLine();
        $expr = $this->parser->parseExpression();
        $stream = $this->parser->getStream();
        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideIfFork']);
        $tests = [$expr, $body];
        $else = null;

        $end = false;
        while (!$end) {
            switch ($stream->next()->getValue()) {
                case 'cannotelse':
                    $stream->expect(Token::BLOCK_END_TYPE);
                    $else = $this->parser->subparse([$this, 'decideIfEnd']);
                    break;

                case 'endcannot':
                    $end = true;
                    break;

                default:
                    throw new SyntaxError(\sprintf('Unexpected end of template. Twig was looking for the following tags "else", "elseif", or "endif" to close the "if" block started at line %d).', $lineno), $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        return new CannotNode(new Nodes($tests), $else, $lineno);
    }

    public function decideIfFork(Token $token): bool
    {
        return $token->test(['cannotelse', 'endcannot']);
    }

    public function decideIfEnd(Token $token): bool
    {
        return $token->test(['endcannot']);
    }

    public function getTag()
    {
        return 'cannot';
    }
}
