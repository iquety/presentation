<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Smarty\Tags;

use Smarty\Compile\Base;
use Smarty\Compiler\Template;

class CanElseTag extends Base {

	/**
	 * Compiles code for the {else} tag
	 *
	 * @param array $args array with attributes from parser
	 * @param Template $compiler compiler object
	 *
	 * @return string compiled code
	 */
	public function compile($args, Template $compiler, $parameter = [], $tag = null, $function = null): string
	{
		[$nesting, $compiler->tag_nocache] = $this->closeTag($compiler, ['can']);

		$this->openTag($compiler, 'canelse', [$nesting, $compiler->tag_nocache]);
		
		return '<?php } else { ?>';
	}
}