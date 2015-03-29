<?php

namespace TomPHP\HalClient\HttpClient;

use TomPHP\HalClient\HttpClient;
use GuzzleHttp\Client;
use TomPHP\HalClient\HttpResponse;

final class GuzzleHttpClient implements HttpClient
{
    /** @var string */
    private $dbPath;

    public function __construct()
    {
        $this->dbPath = __DIR__ . '/../../testapi/endpoints.db';

        $this->writeEndpoints([]);
    }

    /**
     * @param string $method
     * @param string $url
     * @param string $contentType
     * @param string $body
     */
    public function createEndpoint($method, $url, $contentType, $body)
    {
        $endpoints = unserialize(file_get_contents($this->dbPath));

        $endpoints[$method][$url] = [
            'contentType' => $contentType,
            'body'        => $body
        ];

        $this->writeEndpoints($endpoints);
    }

    private function writeEndpoints($endpoints)
    {
        $fp = fopen($this->dbPath, 'w');

        if (!$fp) {
            throw new \RuntimeException("Fail to open {$this->dbPath} for writing.");
        }

        fputs($fp, serialize($endpoints));
        fflush($fp);
        fclose($fp);
    }

    public function get($url)
    {
        $client = new Client();

        $response = $client->get('http://localhost:1080' . $url);

        return new HttpResponse(
            $response->getHeader('content-type'),
            (string) $response->getBody()
        );
    }
}