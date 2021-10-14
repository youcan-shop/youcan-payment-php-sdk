<?php

namespace YouCan\Pay\API;

use YouCan\Pay\API\Exceptions\InvalidResponseException;
use YouCan\Pay\API\HTTPAdapter\HTTPAdapter;
use YouCan\Pay\API\HTTPAdapter\HTTPAdapterPicker;

final class APIService implements APIServiceInterface
{
    public static $isSandboxMode = false;

    /** @var HTTPAdapter */
    private $httpAdapter;

    /** @var string */
    private $privateKey;

    /** @var string */
    private $publicKey;

    public function __construct(HTTPAdapterPicker $adapterPicker)
    {
        $this->httpAdapter = $adapterPicker->pickAdapter(self::$isSandboxMode);
    }

    public function useKeys(string $privateKey, string $publicKey): void
    {
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @return Response
     * @throws InvalidResponseException
     */
    public function post(string $endpoint, array $params = []): Response
    {
        return $this->getHttpAdapter()->post($endpoint,$params);
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @return Response
     * @throws InvalidResponseException
     */
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
        static::$isSandboxMode = $isSandboxMode;
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
