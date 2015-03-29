<?php

namespace TomPHP\HalClient;

use TomPHP\HalClient\Exception\UnknownContentTypeException;
use Assert\Assertion;

final class Client
{
    /** @var HttpClient */
    private $httpClient;

    /** @var Processor[] */
    private $processors;

    /** @param Processor[] $processors */
    public function __construct(HttpClient $httpClient, array $processors)
    {
        Assertion::allIsInstanceOf($processors, Processor::class);

        $this->httpClient = $httpClient;

        foreach ($processors as $processor) {
            $this->processors[$processor->getContentType()] = $processor;
        }
    }

    /**
     * @param string $url
     *
     * @return Response
     */
    public function get($url)
    {
        $response = $this->httpClient->get($url);

        $contentType = $response->getContentType();

        if (!array_key_exists($contentType, $this->processors)) {
            throw new UnknownContentTypeException($contentType);
        }

        $processor = $this->processors[$contentType];

        return $processor->process($response);
    }
}
