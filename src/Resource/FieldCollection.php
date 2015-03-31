<?php

namespace TomPHP\HalClient\Resource;

final class FieldCollection implements FieldNode
{
    /**
     * @param mixed[] $values
     *
     * @return self
     */
    public static function fromArray(array $values)
    {
        return new self();
    }
}
