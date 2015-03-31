<?php

namespace TomPHP\HalClient\HttpClient;

use TomPHP\HalClient\HttpClient;
use Phly\Http\Response;

final class DummyHttpClient implements HttpClient
{
    const METHOD_GET = 'GET';

    /** @var array */
    private $endpoints = [
        self::METHOD_GET => []
    ];

    /**
     * @param string $method
     * @param string $url
     * @param string $contentType
     * @param string $body
     */
    public function createEndpoint($method, $url, $contentType, $body)
    {
        $this->endpoints[$method][$url] = new Response(
            "data://text/plain,$body",
            200,
            ['content-type' => $contentType]
        );
    }

    public function get($url)
    {
        return $this->endpoints[self::METHOD_GET][$url];
    }
}
