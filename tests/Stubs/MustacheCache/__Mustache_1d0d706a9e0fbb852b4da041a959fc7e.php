<?php

class __Mustache_1d0d706a9e0fbb852b4da041a959fc7e extends \Mustache\Template
{
    protected $strictCallables = true;
    public function renderInternal(\Mustache\Context $context, $indent = '')
    {
        $buffer = '';

        $buffer .= $indent . 'Hello, ';
        $value = $this->resolveValue($context->find('name'), $context);
        $buffer .= ($value === null ? '' : htmlspecialchars($value, 3, 'UTF-8'));
        $buffer .= '!';

        return $buffer;
    }
}
