<?php

namespace TomPHP\HalClient;

interface Processor
{
    /** @return string */
    public function getContentType();

    /** @return Resource */
    public function process(HttpResponse $response, ResourceFetcher $fetcher);
}
