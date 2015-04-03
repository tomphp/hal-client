<?php

namespace TomPHP\HalClient\Resource;

use Assert\Assertion;
use TomPHP\HalClient\Exception\FieldNotFoundException;
use TomPHP\HalClient\Exception\LinkNotFoundException;
use TomPHP\HalClient\Exception\ResourceNotFoundException;

final class Resource implements ResourceNode
{
    /** @var array */
    private $fields = [];

    /** @var Link[] */
    private $links = [];

    /** @var Resource[] */
    private $resources = [];

    /**
     * @param FieldNode[]    $fields
     * @param Link[]         $links
     * @param ResourceNode[] $resources
     */
    public function __construct(array $fields, array $links = [], array $resources = [])
    {
        Assertion::allIsInstanceOf($fields, FieldNode::class);
        Assertion::allIsInstanceOf($links, Link::class);
        Assertion::allIsInstanceOf($resources, ResourceNode::class);

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

    /** @todo test me! */
    public function matches($criteria)
    {
        $result = true;

        foreach ($criteria as $name => $value) {
            if (!array_key_exists($name, $this->fields)) {
                $result = false;
                break;
            }

            $result = $result && $this->fields[$name]->matches($value);
        }

        return $result;
    }
}
