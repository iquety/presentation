<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Smarty\Tags;

use Smarty\Compile\Base;
use Smarty\Compiler\Template;

class CannotClose extends Base {

	/**
	 * Compiles code for the {/can} tag
	 *
	 * @param array $args array with attributes from parser
	 * @param Template $compiler compiler object
	 *
	 * @return string compiled code
	 */
	public function compile($args, Template $compiler, $parameter = [], $tag = null, $function = null): string
	{
		[$nesting, $nocache_pushed] = $this->closeTag($compiler, ['cannot', 'cannotelse']);

		if ($nocache_pushed) {
			// pop the pushed virtual nocache tag
			$this->closeTag($compiler, 'nocache');
			$compiler->tag_nocache = true;
		}

		$tmp = '';
		for ($i = 0; $i < $nesting; $i++) {
			$tmp .= '}';
		}
		return "<?php {$tmp}?>";
	}
}
