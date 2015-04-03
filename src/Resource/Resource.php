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
     * @return FieldNode
     */
    public function __get($name)
    {
        return $this->getField($name);
    }

    /**
     * @param string $name
     *
     * @return FieldNode
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

    public function matches($criteria)
    {
        foreach ($criteria as $name => $value) {
            if (!$this->matchesItem($name, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string|int $name
     * @param mixed      $value
     *
     * @return bool
     */
    private function matchesItem($name, $value)
    {
        if ($this->isResourceSearchCriteria($name, $value)) {
            return $this->matchesResource($value);
        }

        return $this->matchField($name, $value);
    }

    /**
     * @param string|int $name
     * @param mixed      $value
     *
     * @return bool
     */
    private function isResourceSearchCriteria($name, $value)
    {
        return is_int($name)
            && is_array($value)
            && array_key_exists(0, $value)
            && $value[0] === 'resource';
    }

    /** @return bool */
    private function matchesResource(array $value)
    {
        $name = $value[1];
        $search = $value[2];

        if (!array_key_exists($name, $this->resources)) {
            return false;
        }

        return $this->resources[$name]->matches($search);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return bool
     */
    public function matchField($name, $value)
    {
        if (!array_key_exists($name, $this->fields)) {
            return false;
        }

        return $this->fields[$name]->matches($value);
    }
}
