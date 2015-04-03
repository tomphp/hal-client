<?php

namespace TomPHP\HalClient\Resource;

use Assert\Assertion;
use TomPHP\HalClient\Exception\MutabilityException;

trait Collection
{
    /** @var Matchable[] */
    protected $items;

    /** @var Matchable[] */
    public function __construct(array $items)
    {
        Assertion::allIsInstanceOf($items, Matchable::class);
        $this->assertItemTypesAreCorrect($items);

        $this->items = $items;
    }

    /**
     * @param array $criteria
     *
     * @return static
     */
    public function findMatching(array $criteria)
    {
        return new static(array_values(array_filter(
            $this->items,
            function (Matchable $node) use ($criteria) {
                return $node->matches($criteria);
            }
        )));
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
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

    public function count()
    {
        return count($this->items);
    }

    public function matches($criteria)
    {
        return false;
    }

    abstract protected function assertItemTypesAreCorrect(array $items);
}
