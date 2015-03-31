<?php

namespace TomPHP\HalClient\Resource;

use ArrayAccess;

final class ResourceCollection implements ResourceNode, ArrayAccess
{
    public function offsetExists($offset)
    {
    //    return isset($this->fields[$offset]);
    }

    public function offsetGet($offset)
    {
    //    return $this->fields[$offset];
    }

    /**
     * @throws MutabilityException
     */
    public function offsetSet($offset, $value)
    {
    //    throw new MutabilityException();
    }

    /**
     * @throws MutabilityException
     */
    public function offsetUnset($offset)
    {
    //    throw new MutabilityException();
    }
}
