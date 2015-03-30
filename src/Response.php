<?php

namespace TomPHP\HalClient;

use Assert\Assertion;
use TomPHP\HalClient\Response\Link;
use TomPHP\HalClient\Exception\FieldNotFoundException;
use TomPHP\HalClient\Exception\LinkNotFoundException;
use TomPHP\HalClient\Response\Field;
use TomPHP\HalClient\Response;
use TomPHP\HalClient\Exception\ResourceNotFoundException;

final class Response
{
    /** @var array */
    private $fields = [];

    /** @var Link[] */
    private $links = [];

    /** @var Response[] */
    private $resources = [];

    /**
     * @param Field[]    $fields
     * @param Link[]     $links
     * @param Response[] $resources
     */
    public function __construct(array $fields, array $links = [], array $resources = [])
    {
        Assertion::allIsInstanceOf($fields, Field::class);
        Assertion::allIsInstanceOf($links, Link::class);
        Assertion::allIsInstanceOf($resources, Response::class);

        foreach ($fields as $field) {
            $this->fields[$field->name()] = $field;
        }

        foreach ($links as $link) {
            $this->links[$link->name()] = $link;
        }

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
            return $this->field($name);
        }

        if (array_key_exists($name, $this->resources)) {
            return $this->resource($name);
        }

        return $this->link($name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws FieldNotFoundException
     */
    public function field($name)
    {
        if (!array_key_exists($name, $this->fields)) {
            throw new FieldNotFoundException($name);
        }

        return $this->fields[$name];
    }

    /** @return string[] */
    public function links()
    {
        return array_keys($this->links);
    }

    /**
     * @return Link
     *
     * @throws LinkNotFoundException
     */
    public function link($name)
    {
        if (!array_key_exists($name, $this->links)) {
            throw new LinkNotFoundException($name);
        }

        return $this->links[$name];
    }

    /**
     * @return Resource
     */
    public function resource($name)
    {
        if (!array_key_exists($name, $this->resources)) {
            throw new ResourceNotFoundException($name);
        }

        return $this->resources[$name];
    }
}
