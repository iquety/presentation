<?php

declare(strict_types=1);

namespace Iquety\Presentation;

use Iquety\Presentation\Engine\TemplateEngine;

class Presentation
{
    public function __construct(private TemplateEngine $engine)
    {
    }

    public function render(string $template, array $data = []): string
    {
        return $this->engine->render($template, $data);
    }
}

