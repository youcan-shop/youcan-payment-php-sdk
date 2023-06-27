<?php

namespace YouCan\Pay\API\Endpoints;

use InvalidArgumentException;
use YouCan\Pay\API\APIServiceInterface;
use YouCan\Pay\API\Exceptions\Keys\UnsetPrivateKeyException;
use YouCan\Pay\API\Exceptions\Keys\UnsetPublicKeyException;

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
            throw new UnsetPrivateKeyException("private key not set");
        }
    }

    protected function assertPublicKeyIsSet(): void
    {
        if ($this->apiService->getPublicKey() === null) {
            throw new UnsetPublicKeyException("public key not set");
        }
    }
}
