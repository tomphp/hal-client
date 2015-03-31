<?php

namespace TomPHP\HalClient;

use Psr\Http\Message\ResponseInterface;

interface Processor
{
    /** @return string */
    public function getContentType();

    /** @return Resource */
    public function process(ResponseInterface $response, ResourceFetcher $fetcher);
}
