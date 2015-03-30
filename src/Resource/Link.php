<?php

namespace TomPHP\HalClient\Resource;

use TomPHP\HalClient\ResourceFetcher;

final class Link
{
    /** @var ResourceFetcher */
    private $fetcher;

    /** @var string */
    private $name;

    /** @var string */
    private $href;

    /**
     * @param string $name
     * @param string $href
     */
    public function __construct(ResourceFetcher $fetcher, $name, $href)
    {
        $this->fetcher = $fetcher;
        $this->name    = $name;
        $this->href    = $href;
    }

    /** @return string */
    public function name()
    {
        return $this->name;
    }

    /** @return Resource */
    public function get()
    {
        return $this->fetcher->get($this->href);
    }
}
