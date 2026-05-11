<?php

namespace YouCan\Pay\API\Exceptions;

use RuntimeException;
use Throwable;

class BaseException extends RuntimeException
{
    protected ?string $response;

    public function __construct(
        string $message,
        ?string $response = null,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->response = $response;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function hasResponse(): bool
    {
        return $this->response !== null;
    }
}
