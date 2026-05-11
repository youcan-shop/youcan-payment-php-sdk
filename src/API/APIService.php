<?php

namespace YouCan\Pay\API;

use Exception;
use YouCan\Pay\API\HTTPAdapter\HTTPAdapter;
use YouCan\Pay\API\HTTPAdapter\HTTPAdapterPicker;

final class APIService implements APIServiceInterface
{
    public static bool $isSandboxMode = false;

    private HTTPAdapter $httpAdapter;

    private ?string $privateKey = null;

    private ?string $publicKey = null;

    /**
     * @throws Exception
     */
    public function __construct(HTTPAdapterPicker $adapterPicker)
    {
        $this->httpAdapter = $adapterPicker->pickAdapter(self::$isSandboxMode);
    }

    public function useKeys(string $privateKey, string $publicKey): void
    {
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
    }

    public function post(string $endpoint, array $params = []): Response
    {
        return $this->getHttpAdapter()->post($endpoint, $params);
    }

    public function get(string $endpoint, array $params = []): Response
    {
        return $this->getHttpAdapter()->get($endpoint, $params);
    }

    private function getHttpAdapter(): HTTPAdapter
    {
        return $this->httpAdapter;
    }

    public static function setIsSandboxMode(bool $isSandboxMode): void
    {
        self::$isSandboxMode = $isSandboxMode;
    }

    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }

    public function getPrivateKey(): ?string
    {
        return $this->privateKey;
    }
}
