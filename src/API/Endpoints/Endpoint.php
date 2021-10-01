<?php

namespace YouCan\Pay\API\Endpoints;

use InvalidArgumentException;
use YouCan\Pay\API\APIServiceInterface;

abstract class Endpoint
{
    /** @var APIServiceInterface */
    protected $apiService;

    public function __construct(APIServiceInterface $apiService)
    {
        $this->apiService = $apiService;
    }

    abstract protected function endpoint(): string;

    protected function listEndpoint(): string
    {
        return $this->endpoint();
    }

    protected function singleEndpoint(string $id): string
    {
        return sprintf("%s/%s", $this->endpoint(), $id);
    }

    protected function createEndpoint(): string
    {
        return $this->endpoint();
    }

    protected function assertPrivateKeyIsSet(): void
    {
        if ($this->apiService->getPrivateKey() === null) {
            throw new InvalidArgumentException("private key not set");
        }
    }

    protected function assertPublicKeyIsSet(): void
    {
        if ($this->apiService->getPublicKey() === null) {
            throw new InvalidArgumentException("public key not set");
        }
    }
}
