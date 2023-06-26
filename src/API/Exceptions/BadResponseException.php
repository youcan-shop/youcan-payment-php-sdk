<?php

namespace YouCan\Pay\API\Exceptions;

use Throwable;

class BadResponseException extends RequestException
{
    public function __construct(
        string $message,
        string $response = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $response, $code, $previous);
    }

    public function hasResponse(): bool
    {
        return true;
    }

    public function getResponse()
    {
        return parent::getResponse();
    }
}
