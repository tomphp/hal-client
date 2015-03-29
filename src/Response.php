<?php

namespace TomPHP\HalClient;

use Assert\Assertion;
use TomPHP\HalClient\Response\Link;
use TomPHP\HalClient\Exception\FieldNotFoundException;

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
    public function __construct(array $fields, array $links)
    {
        Assertion::allIsInstanceOf($links, Link::class);

        $this->fields = $fields;

        foreach ($links as $link) {
            $this->links[$link->getName()] = $links;
        }
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->field($name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function field($name)
    {
        if (!array_key_exists($name, $this->fields)) {
            throw new FieldNotFoundException($name);
        }

        return $this->fields[$name];
    }

    public function links()
    {
        return array_keys($this->links);
    }
}
