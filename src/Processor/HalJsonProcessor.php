<?php

namespace TomPHP\HalClient\Processor;

use TomPHP\HalClient\Processor;
use TomPHP\HalClient\Resource;
use TomPHP\HalClient\HttpResponse;
use TomPHP\HalClient\Resource\Link;
use TomPHP\HalClient\ResourceFetcher;
use TomPHP\HalClient\Resource\Field;
use TomPHP\HalClient\Resource\FieldMap;

final class HalJsonProcessor implements Processor
{
    /** @var array */
    private $data;

    /** @var ResourceFetcher */
    private $fetcher;

    public function getContentType()
    {
        return 'application/hal+json';
    }

    /** @return Resource */
    public function process(HttpResponse $httpResource, ResourceFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
        $this->data    = json_decode($httpResource->getBody());

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

            if (is_object($value)) {
                $fields[$name] = FieldMap::fromObject($value);
            } else {
                $fields[$name] = new Field($value);
            }
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
                isset($params->rel) ?: null
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

        $processor = new self();

        foreach ($this->data->_embedded as $name => $params) {
            $resources[$name] = $processor->process(
                new HttpResponse($this->getContentType(), json_encode($params)),
                $this->fetcher
            );
        }

        return $resources;
    }
}
