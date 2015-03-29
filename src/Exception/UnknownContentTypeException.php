<?php

namespace TomPHP\HalClient\Exception;

use RuntimeException;

final class UnknownContentTypeException extends RuntimeException implements
    HalClientException
{
}
