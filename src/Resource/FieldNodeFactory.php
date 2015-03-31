<?php

namespace TomPHP\HalClient\Resource;

trait FieldNodeFactory
{
    /**
     * @param mixed $value
     *
     * @return FileNode
     */
    protected static function createFieldNode($value)
    {
        if (is_object($value)) {
            return FieldMap::fromObject($value);
        }

        if (is_array($value)) {
            return FieldCollection::fromArray($value);
        }

        return new Field($value);
    }
}
