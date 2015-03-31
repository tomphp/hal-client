<?php

namespace TomPHP\HalClient\Resource;

use stdClass;
use TomPHP\HalClient\Resource\FieldNode;
use TomPHP\HalClient\Exception\FieldNotFoundException;

final class FieldMap implements FieldNode
{
    use FieldNodeFactory;

    /** @var FieldNode[] */
    private $fields;

    /** @return self */
    public static function fromObject(stdClass $object)
    {
        $fields = [];

        foreach ($object as $property => $value) {
            $fields[$property] = self::createFieldNode($value);
        }

        return new self($fields);
    }

    /** @param FieldNode[] $fields */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
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
}
