<?php

class __Mustache_dab7ec3612eecd10e5e9fd5881669073 extends \Mustache\Template
{
    protected $strictCallables = true;
    public function renderInternal(\Mustache\Context $context, $indent = '')
    {
        $buffer = '';

        $buffer .= $indent . 'Bye, ';
        $value = $this->resolveValue($context->find('name'), $context);
        $buffer .= ($value === null ? '' : htmlspecialchars($value, 3, 'UTF-8'));
        $buffer .= '!';

        return $buffer;
    }
}
