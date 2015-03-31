<?php

namespace TomPHP\HalClient;

use Psr\Http\Message\ResponseInterface;

interface HttpClient
{
    /**
     * @param string $url
     *
     * @return ResponseInterface
     */
    public function get($url);
}
