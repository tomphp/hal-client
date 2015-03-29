<?php

namespace TomPHP\HalClient;

interface ResponseFetcher
{
    /**
     * @param string $url
     *
     * @return Response
     */
    public function get($url);
}
