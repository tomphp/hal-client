<?php

namespace TomPHP\HalClient\Resource;

use ArrayAccess;
use Assert\Assertion;
use TomPHP\HalClient\Exception\MutabilityException;

final class NodeCollection extends Node implements ArrayAccess
{
    /** @var Node[] */
    private $nodes;

    public function __construct(array $nodes)
    {
        Assertion::allIsInstanceOf($nodes, Node::class);

        $this->nodes = $nodes;
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
}
