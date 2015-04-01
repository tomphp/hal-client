<?php

namespace TomPHP\HalClient\Processor;

use Phly\Http\Stream;
use Psr\Http\Message\ResponseInterface;
use TomPHP\HalClient\Processor;
use TomPHP\HalClient\Resource\Resource;
use TomPHP\HalClient\ResourceFetcher;
use TomPHP\HalClient\Resource\Field;
use TomPHP\HalClient\Resource\FieldMap;
use TomPHP\HalClient\Resource\FieldNodeFactory;
use TomPHP\HalClient\Resource\Link;
use TomPHP\HalClient\Resource\NodeCollection;
use TomPHP\HalClient\Resource\ResourceCollection;
use stdClass;
use TomPHP\HalClient\Exception\ProcessingException;

final class HalJsonProcessor implements Processor
{
    /** @var ResponseInterface */
    private $response;

    /** @var array */
    private $data;

    /** @var ResourceFetcher */
    private $fetcher;

    public function getContentType()
    {
        return 'application/hal+json';
    }

    /** @return Resource */
    public function process(ResponseInterface $response, ResourceFetcher $fetcher)
    {
        $this->response = $response;
        $this->fetcher  = $fetcher;
        $this->data     = json_decode($response->getBody());

        if ($this->data === null) {
            throw ProcessingException::badJson(json_last_error_msg());
        }

        return new Resource(
            $this->getFields(),
            $this->getLinks(),
            $this->getResources()
        );
    }

    /** @return Field[] */
    private function getFields()
    {
        $fields = [];

        foreach ($this->data as $name => $value) {
            if ($name === '_links' || $name === '_embedded') {
                continue;
            }

            $fields[$name] = self::createFieldNode($value);
        }

        return $fields;
    }

    /** @return Link[] */
    private function getLinks()
    {
        if (!isset($this->data->_links)) {
            return [];
        }

        $links = [];

        foreach ($this->data->_links as $name => $params) {
            $links[$name] = new Link(
                $this->fetcher,
                $params->href,
                isset($params->rel) ? $params->rel : null
            );
        }

        return $links;
    }

    /** @return Resource[] */
    private function getResources()
    {
        if (!isset($this->data->_embedded)) {
            return [];
        }

        $resources = [];

        foreach ($this->data->_embedded as $name => $params) {
            if (is_array($params)) {
                $resources[$name] = new NodeCollection(array_map(
                    function ($params) {
                        return $this->createResourceFromObject($params);
                    },
                    $params
                ));
                continue;
            }

            $resources[$name] = $this->createResourceFromObject($params);
        }

        return $resources;
    }

    /** @return Resource */
    private function createResourceFromObject(stdClass $object)
    {
        $processor = new self();

        return $processor->process(
            $this->response->withBody($this->createStream(json_encode($object))),
            $this->fetcher
        );
    }

    /** @return Stream */
    private function createStream($data)
    {
        return new Stream("data://text/plain,$data");
    }

    /**
     * @param mixed $value
     *
     * @return FileNode
     */
    private function createFieldNode($value)
    {
        if (is_object($value)) {
            return $this->createFieldMapFromObject($value);
        }

        if (is_array($value)) {
            return $this->fromArray($value);
        }

        return new Field($value);
    }

    /** @return FieldMap */
    private function createFieldMapFromObject(stdClass $object)
    {
        $fields = [];

        foreach ($object as $property => $value) {
            $fields[$property] = $this->createFieldNode($value);
        }

        return new FieldMap($fields);
    }

    /**
     * @param mixed[] $values
     *
     * @return NodeCollection
     */
    public function fromArray(array $values)
    {
        return new NodeCollection(array_map(function ($field) {
            return $this->createFieldNode($field);
        }, $values));
    }
}
