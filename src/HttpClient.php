<?php

namespace TomPHP\HalClient;

interface HttpClient
{
    /**
     * @param string $url
     *
     * @return HttpResponse
     */
    public function get($url);
}
