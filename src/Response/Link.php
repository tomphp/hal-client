<?php

namespace TomPHP\HalClient\Response;

use TomPHP\HalClient\ResponseFetcher;

final class Link
{
    /** @var ResponseFetcher */
    private $fetcher;

    /** @var string */
    private $name;

    /** @var string */
    private $href;

    /**
     * @param string $name
     * @param string $href
     */
    public function __construct(ResponseFetcher $fetcher, $name, $href)
    {
        $this->fetcher = $fetcher;
        $this->name    = $name;
        $this->href    = $href;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    /** @return Response */
    public function get()
    {
        return $this->fetcher->get($this->href);
    }
}
