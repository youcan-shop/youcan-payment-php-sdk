<?php

namespace YouCan\Pay\API;

class Response
{
    private int $statusCode;

    private array $response;

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

    public function get(string $key): mixed
    {
        return $this->getResponse()[$key] ?? null;
    }
}
