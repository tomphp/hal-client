<?php

namespace TomPHP\HalClient\Processor;

use TomPHP\HalClient\Processor;
use TomPHP\HalClient\Response;
use TomPHP\HalClient\HttpResponse;
use TomPHP\HalClient\Response\Link;
use TomPHP\HalClient\ResponseFetcher;
use TomPHP\HalClient\Response\Field;

final class HalJsonProcessor implements Processor
{
    /** @var array */
    private $data;

    /** @var ResponseFetcher */
    private $fetcher;

    public function getContentType()
    {
        return 'application/hal+json';
    }

    /** @return Response */
    public function process(HttpResponse $httpResponse, ResponseFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
        $this->data    = json_decode($httpResponse->getBody(), true);

        return new Response(
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

            $fields[] = new Field($name, $value);
        }

        return $fields;
    }

    /** @return Link[] */
    private function getLinks()
    {
        if (!array_key_exists('_links', $this->data)) {
            return [];
        }

        $links = [];

        foreach ($this->data['_links'] as $name => $params) {
            $links[] = new Link(
                $this->fetcher,
                $name,
                $params['href'],
                (isset($params['rel']) ? $params['rel'] : null)
            );
        }

        return $links;
    }

    /** @return Resource[] */
    private function getResources()
    {
        if (!array_key_exists('_embedded', $this->data)) {
            return [];
        }

        $resources = [];

        $processor = new self();

        foreach ($this->data['_embedded'] as $name => $params) {
            $resources[$name] = $processor->process(
                new HttpResponse($this->getContentType(), json_encode($params)),
                $this->fetcher
            );
        }

        return $resources;
    }
}
