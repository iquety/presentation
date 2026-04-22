<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Blade;

use eftec\bladeone\BladeOne;
use InvalidArgumentException;

class Blade extends BladeOne
{
    #[\Override]
    protected function compileCan($expression): string
    {
        $value = $this->stripParentheses($expression);

        if (str_contains('-', $value) === true) {
            throw new InvalidArgumentException('The permission format is incompatible with PHP variables.');
        }

        $permission = 'permission_' . str_replace(['"', "'"], '', $value);

        return $this->phpTag
            . "if (\n"
            . "    isset(\$$permission) === true\n"
            . "    && (\n"
            . "        \$$permission === 'true'\n"
            . "        || \$$permission === true\n"
            . "        || \$$permission === '1'\n"
            . "        || \$$permission === 1\n"
            . "    )\n"
            . "): ?>";
    }

    /**
     * Compile the else statements into valid PHP.
     *
     * @return string
     */
    protected function compileCanelse(): string
    {
        return $this->phpTag . 'else: ?>';
    }

    #[\Override]
    protected function compileCannot($expression): string
    {
        $value = $this->stripParentheses($expression);

        if (str_contains('-', $value) === true) {
            throw new InvalidArgumentException('The permission format is incompatible with PHP variables.');
        }

        $permission = 'permission_' . str_replace(['"', "'"], '', $value);

        return $this->phpTag
            . "if (\n"
            . "    isset(\$$permission) === true\n"
            . "    && (\n"
            . "        \$$permission === ''\n"
            . "        || \$$permission === 'false'\n"
            . "        || \$$permission === false\n"
            . "        || \$$permission === '0'\n"
            . "        || \$$permission === 0\n"
            . "    )\n"
            . "): ?>";
    }

    /**
     * Compile the elsecannot statements into valid PHP.
     *
     * @return string
     */
    protected function compileCannotelse(): string
    {
        return $this->phpTag . 'else: ?>';
    }


    /**
     * Compile the else statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    #[\Override]
    protected function compileElseCan($expression = ''): string
    {
        return "@elseCan";
    }
    
    /**
     * Compile the elsecannot statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    #[\Override]
    protected function compileElseCannot($expression = ''): string
    {
        return "@elseCannot";
    }
}