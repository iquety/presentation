<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Plates;

use League\Plates\Engine;

class Plates extends Engine
{
    /**
     * Create a new template.
     * @param  string   $name
     * @param  array    $data
     * @return Template
     */
    public function make($name, array $data = array())
    {
        $template = new Template($this, $name);
        $template->data($data);
        return $template;
    }
}

