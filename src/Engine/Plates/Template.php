<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Plates;

use League\Plates\Template\Template as PlatesTemplate;

class Template extends PlatesTemplate
{
    /**
     * Start a new section block.
     * @param  string  $name
     */
    public function can($name): bool
    {
        $permission = 'permission-' . $name;

        return isset($this->data[$permission]) === true
            && (
                $this->data[$permission] === 'true'
                || $this->data[$permission] === true
                || $this->data[$permission] === '1'
                || $this->data[$permission] === 1
            );
    }

    /**
     * Start a new section block.
     * @param  string  $name
     */
    public function cannot($name): bool
    {
        $permission = 'permission-' . $name;

        return isset($this->data[$permission]) === true
            && (
                $this->data[$permission] === 'false'
                || $this->data[$permission] === false
                || $this->data[$permission] === '0'
                || $this->data[$permission] === 0
            );
    }
}

