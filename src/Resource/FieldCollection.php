<?php

namespace TomPHP\HalClient\Resource;

use ArrayAccess;
use Assert\Assertion;
use Countable;
use TomPHP\HalClient\Exception\MutabilityException;

/**
 * @method FieldNode function findMatching(array $criteria)
 * @method FieldNode function offsetExists(int $offset)
 * @method FieldNode function offsetGet(int $offset)
 */
final class FieldCollection implements FieldNode, ArrayAccess, Countable
{
    use Collection;

    protected function assertItemTypesAreCorrect(array $items)
    {
        Assertion::allIsInstanceOf($items, FieldNode::class);

        $this->items = $items;
    }
}
