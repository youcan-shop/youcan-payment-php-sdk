<?php

namespace YouCan\Pay\API\Exceptions;

use Exception;

class InvalidResponseException extends Exception
{
    /** @var int */
    private $responseStatus;

    /** @var string */
    private $responseBody;

    public function __construct(int $responseStatus, string $responseBody, string $message = null)
    {
        parent::__construct($message ?: "invalid response from YouCan Pay API");

        $this->responseStatus = $responseStatus;
        $this->responseBody = $responseBody;
    }

    public function getResponseBody(): string
    {
        return $this->responseBody;
    }

    public function getResponseStatus(): int
    {
        return $this->responseStatus;
    }
}
