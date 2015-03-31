<?php

namespace TomPHP\HalClient\Resource;

use ArrayAccess;
use Assert\Assertion;

final class FieldCollection implements FieldNode, ArrayAccess
{
    /** @var Field[] */
    private $fields;

    /**
     * @param mixed[] $values
     *
     * @return self
     */
    public static function fromArray(array $values)
    {
        return new self($values);
    }

    public function __construct(array $fields)
    {
        Assertion::allIsInstanceOf($fields, FieldNode::class);

        $this->fields = $fields;
    }

    public function offsetExists($offset)
    {
        return isset($this->fields[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->fields[$offset];
    }

    public function offsetSet($offset, $value)
    {
    }

    public function offsetUnset($offset)
    {
    }
}
