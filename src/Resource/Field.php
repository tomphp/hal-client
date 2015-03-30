<?php

namespace TomPHP\HalClient\Resource;

final class Field implements FieldNode
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /** @return string */
    public function value()
    {
        return $this->value;
    }
}
