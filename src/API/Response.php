<?php

namespace YouCan\Pay\API;

class Response
{
    /** @var int */
    private $statusCode;

    /** @var array */
    private $response;

    public function __construct(int $statusCode, array $response)
    {
        $this->statusCode = $statusCode;
        $this->response = $response;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * Get a value from response body
     *
     * @param string $key
     */
    public function get(string $key)
    {
        return $this->getResponse()[$key] ?? null;
    }
}