<?php

namespace TomPHP\HalClient\Processor;

use TomPHP\HalClient\Processor;
use TomPHP\HalClient\Response;
use TomPHP\HalClient\HttpResponse;
use TomPHP\HalClient\Response\Link;
use TomPHP\HalClient\ResponseFetcher;

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
            $this->getData(),
            $this->getLinks()
        );
    }

    public function getData()
    {
        $data = $this->data;

        unset($data['_links']);

        return $data;
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
}
