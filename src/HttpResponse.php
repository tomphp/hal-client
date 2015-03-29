<?php

namespace TomPHP\HalClient;

final class HttpResponse
{
    /** @var string */
    private $contentType;

    /** @var string */
    private $body;

    /**
     * @param string $contentType
     * @param string $body
     */
    public function __construct($contentType, $body)
    {
        $this->contentType = $contentType;
        $this->body        = $body;
    }

    /** @return string */
    public function getContentType()
    {
        return $this->contentType;
    }

    /** @return string */
    public function getBody()
    {
        return $this->body;
    }
}
