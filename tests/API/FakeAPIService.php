<?php

namespace Tests\API;

use YouCan\Pay\API\APIServiceInterface;
use YouCan\Pay\API\Response;

class FakeAPIService implements APIServiceInterface
{
    public static $isSandboxMode = false;

    /** @var FakeAPIAdapter */
    private $httpAdapter;

    public function __construct(Response $response)
    {
        $this->response = $response;

        $this->httpAdapter = new FakeAPIAdapter(self::$isSandboxMode);
        $this->httpAdapter->setFakeResponse($response);
    }

    public function post(string $endpoint, array $params = []): Response
    {
        return $this->httpAdapter->get($endpoint, $params);
    }

    public function get(string $endpoint, array $params = []): Response
    {
        return $this->httpAdapter->get($endpoint, $params);
    }

    public static function setIsSandboxMode(bool $isSandboxMode): void
    {
        self::$isSandboxMode = $isSandboxMode;
    }

    public function getPrivateKey(): ?string
    {
        return 'pri_123456789';
    }

    public function getPublicKey(): ?string
    {
        return 'pub_123456789';
    }
}
