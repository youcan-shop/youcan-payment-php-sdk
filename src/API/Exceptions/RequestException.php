<?php

namespace YouCan\Pay\API\Exceptions;

use Throwable;

class RequestException extends BaseException
{
    /** @var string */
    protected $request;

    /** @var string|null */
    protected $response;

    public function __construct(
        string $message,
        string $request,
        string $response = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->request = $request;
        $this->response = $response;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function hasResponse(): bool
    {
        return $this->response !== null;
    }
}
