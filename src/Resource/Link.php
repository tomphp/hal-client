<?php

namespace TomPHP\HalClient\Resource;

use TomPHP\HalClient\ResourceFetcher;

final class Link
{
    /** @var ResourceFetcher */
    private $fetcher;

    /** @var string */
    private $href;

    /**
     * @param string $href
     */
    public function __construct(ResourceFetcher $fetcher, $href)
    {
        $this->fetcher = $fetcher;
        $this->href    = $href;
    }

    /** @return Resource */
    public function get()
    {
        return $this->fetcher->get($this->href);
    }
}
