<?php

namespace TomPHP\HalClient;

interface ResourceFetcher
{
    /**
     * @param string $url
     *
     * @return Resource
     */
    public function get($url);
}
