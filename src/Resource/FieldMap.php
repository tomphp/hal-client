<?php

namespace TomPHP\HalClient\Resource;

use stdClass;
use TomPHP\HalClient\Resource\Node;
use TomPHP\HalClient\Exception\FieldNotFoundException;

final class FieldMap extends Node
{
    /** @var Node[] */
    private $fields;

    /** @param Node[] $fields */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param string $name
     *
     * @return Node
     */
    public function __get($name)
    {
        return $this->getField($name);
    }

    /**
     * @param string $name
     *
     * @return Field
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
