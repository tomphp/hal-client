<?php

namespace TomPHP\HalClient\Response;

final class Field
{
    /** @var string */
    private $name;

    /** @var string */
    private $value;

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    /** @return string */
    public function name()
    {
        return $this->name;
    }

    /** @return string */
    public function value()
    {
        return $this->value;
    }
}
