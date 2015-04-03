<?php

namespace TomPHP\HalClient\Resource;

use ArrayAccess;
use Assert\Assertion;
use Countable;
use TomPHP\HalClient\Exception\MutabilityException;

final class FieldCollection implements FieldNode, ArrayAccess, Countable
{
    /** @var FieldNode[] */
    private $nodes;

    public function __construct(array $nodes)
    {
        Assertion::allIsInstanceOf($nodes, FieldNode::class);

        $this->nodes = $nodes;
    }

    /**
     * @param array $criteria
     *
     * @return self
     */
    public function findMatching(array $criteria)
    {
        return new self(array_values(array_filter(
            $this->nodes,
            function (FieldNode $node) use ($criteria) {
                return $node->matches($criteria);
            }
        )));
    }

    public function offsetExists($offset)
    {
        return isset($this->nodes[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->nodes[$offset];
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
        return count($this->nodes);
    }

    public function matches($criteria)
    {
        return false;
    }
}
