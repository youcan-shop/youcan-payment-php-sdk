<?php

namespace YouCan\Pay\API\Exceptions;

use Throwable;

class BadResponseException extends RequestException
{
    public function __construct(
        string $message,
        string $request,
        string $response,
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $request, $response, $code, $previous);
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
