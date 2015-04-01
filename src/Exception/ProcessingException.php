<?php

namespace TomPHP\HalClient\Exception;

use RuntimeException;

final class ProcessingException extends RuntimeException
{
    /**
     * @param string $jsonError
     *
     * @return self
     */
    public static function badJson($jsonError)
    {
        return new self($jsonError);
    }
}
