<?php

namespace TomPHP\HalClient\Resource;

use ArrayAccess;
use Assert\Assertion;
use TomPHP\HalClient\Exception\MutabilityException;

final class FieldCollection implements FieldNode, ArrayAccess
{
    use FieldNodeFactory;

    /** @var Field[] */
    private $fields;

    /**
     * @param mixed[] $values
     *
     * @return self
     */
    public static function fromArray(array $values)
    {
        return new self(array_map(function ($field) {
            return self::createFieldNode($field);
        }, $values));
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

    /**
     * @throws MutabilityException
     */
    public function offsetSet($offset, $value)
    {
        throw new MutabilityException();
    }

    /**
     * @throws MutabilityException
     */
    public function offsetUnset($offset)
    {
        throw new MutabilityException();
    }
}
