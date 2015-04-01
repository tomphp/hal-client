<?php

namespace TomPHP\HalClient\Resource;

use Assert\Assertion;
use TomPHP\HalClient\Exception\FieldNotFoundException;
use TomPHP\HalClient\Exception\LinkNotFoundException;
use TomPHP\HalClient\Exception\ResourceNotFoundException;
use TomPHP\HalClient\Resource\Field;
use TomPHP\HalClient\Resource\Link;
use TomPHP\HalClient\Resource\Node;

final class Resource extends Node
{
    /** @var array */
    private $fields = [];

    /** @var Link[] */
    private $links = [];

    /** @var Resource[] */
    private $resources = [];

    /**
     * @param Node[] $fields
     * @param Link[] $links
     * @param Node[] $resources
     */
    public function __construct(array $fields, array $links = [], array $resources = [])
    {
        Assertion::allIsInstanceOf($fields, Node::class);
        Assertion::allIsInstanceOf($links, Link::class);
        Assertion::allIsInstanceOf($resources, Node::class);

        $this->fields    = $fields;
        $this->links     = $links;
        $this->resources = $resources;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->fields)) {
            return $this->getField($name);
        }

        if (array_key_exists($name, $this->resources)) {
            return $this->getResource($name);
        }

        return $this->getLink($name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws FieldNotFoundException
     */
    public function getField($name)
    {
        if (!array_key_exists($name, $this->fields)) {
            throw new FieldNotFoundException($name);
        }

        return $this->fields[$name];
    }

    /** @return string[] */
    public function getLinks()
    {
        return array_keys($this->links);
    }

    /**
     * @return Link
     *
     * @throws LinkNotFoundException
     */
    public function getLink($name)
    {
        if (!array_key_exists($name, $this->links)) {
            throw new LinkNotFoundException($name);
        }

        return $this->links[$name];
    }

    /**
     * @return Resource
     */
    public function getResource($name)
    {
        if (!array_key_exists($name, $this->resources)) {
            throw new ResourceNotFoundException($name);
        }

        return $this->resources[$name];
    }
}
