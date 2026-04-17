<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Smarty\Tags;

use Smarty\Compile\Base;
use Smarty\Compiler\Template;
use Smarty\CompilerException;

class CannotTag extends Base {

    /**
     * Compiles code for the {can} tag
     *
     * @param array $args array with attributes from parser
     * @param Template $compiler compiler object
     * @param array $parameter array with compilation parameter
     *
     * @return string compiled code
     * @throws CompilerException
     * @see vendor/smarty/smarty/src/Parser/TemplateParser.php[2382]
     */
    public function compile($args, Template $compiler, $parameter = [], $tag = null, $function = null): string
    {
        if(
            count($args) !== 1 // apenas uma permissão é aceita
            || isset($args[0][0]) === false // deve ser um valor indexado com a permissão
        ) {
            $compiler->trigger_template_error('Incorrect syntax. Use "{cannot \'my-permission\'}"', null, true);
        }

        if ($compiler->tag_nocache) {
            // push a {nocache} tag onto the stack to prevent caching of this block
            $this->openTag($compiler, 'nocache');
        }

        $this->openTag($compiler, 'cannot', [1, $compiler->tag_nocache]);

        $permission = 'permission-' . str_replace(['"', "'"], '', $args[0]);
        
        return "<?php if (\n"
            . "  \$_smarty_tpl->getValue('$permission') !== null\n"
            . "  && (\n"
            . "    \$_smarty_tpl->getValue('$permission') === ''"
            . "    || \$_smarty_tpl->getValue('$permission') === 'false'"
            . "    || \$_smarty_tpl->getValue('$permission') === false"
            . "    || \$_smarty_tpl->getValue('$permission') === '0'"
            . "    || \$_smarty_tpl->getValue('$permission') === 0"
            . "  )\n"
            . ") {?>";
    }
}