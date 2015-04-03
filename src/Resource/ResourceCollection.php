<?php

namespace TomPHP\HalClient\Resource;

use ArrayAccess;
use Assert\Assertion;
use Countable;

/**
 * @method ResourceNode function findMatching(array $criteria)
 * @method ResourceNode function offsetExists(int $offset)
 * @method ResourceNode function offsetGet(int $offset)
 */
final class ResourceCollection implements ResourceNode, ArrayAccess, Countable
{
    use Collection;

    protected function assertItemTypesAreCorrect(array $items)
    {
        Assertion::allIsInstanceOf($items, ResourceNode::class);

        $this->items = $items;
    }
}
