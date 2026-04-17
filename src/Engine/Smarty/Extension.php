<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Smarty;

use Iquety\Presentation\Engine\Smarty\Tags\CanTag;
use Iquety\Presentation\Engine\Smarty\Tags\CanElseTag;
use Iquety\Presentation\Engine\Smarty\Tags\CanClose;

use Iquety\Presentation\Engine\Smarty\Tags\CannotTag;
use Iquety\Presentation\Engine\Smarty\Tags\CannotClose;
use Iquety\Presentation\Engine\Smarty\Tags\CannotElseTag;
use Smarty\Compile\CompilerInterface;
use Smarty\Extension\Base;

/**
 * @see vendor/smarty/smarty/src/Extension/CoreExtension.php
 * @see vendor/smarty/smarty/src/Extension/DefaultExtension.php
 */
class Extension extends Base
{
    public function getTagCompiler(string $tag): ?CompilerInterface
    {
        switch ($tag) {
            case 'can': return new CanTag();
            case 'canelse': return new CanElseTag();
            case 'canclose': return new CanClose();

            case 'cannot': return new CannotTag();
            case 'cannotelse': return new CannotElseTag();
            case 'cannotclose': return new CannotClose();
        }

        return null;
    }
}
