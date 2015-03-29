<?php

namespace TomPHP\HalClient;

use Assert\Assertion;
use TomPHP\HalClient\Response\Link;
use TomPHP\HalClient\Exception\FieldNotFoundException;
use TomPHP\HalClient\Exception\LinkNotFoundException;

final class Response
{
    /** @var array */
    private $fields;

    /** @var Link[] */
    private $links = [];

    /**
     * @param mixed[] $fields
     * @param Link[]  $links
     */
    public function __construct(array $fields, array $links = [])
    {
        Assertion::allIsInstanceOf($links, Link::class);

        $this->fields = $fields;

        foreach ($links as $link) {
            $this->links[$link->getName()] = $link;
        }
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

    /** @return Link */
    public function link($name)
    {
        if (!array_key_exists($name, $this->links)) {
            throw new LinkNotFoundException($name);
        }

        return $this->links[$name];
    }
}
