<?php

namespace TomPHP\HalClient;

interface Processor
{
    /** @return string */
    public function getContentType();

    /** @return Response */
    public function process(HttpResponse $response, ResponseFetcher $fetcher);
}
