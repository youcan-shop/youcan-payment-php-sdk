<?php

namespace YouCan\Pay;

use YouCan\Pay\API\APIService;
use YouCan\Pay\API\APIServiceInterface;
use YouCan\Pay\API\Endpoints\KeysEndpoint;
use YouCan\Pay\API\Endpoints\TokenEndpoint;
use YouCan\Pay\API\Endpoints\TransactionEndpoint;
use YouCan\Pay\API\Exceptions\InvalidWebhookSignatureException;
use YouCan\Pay\API\HTTPAdapter\HTTPAdapterPicker;
use YouCan\Pay\API\Exceptions\InvalidResponseException;

class YouCanPay
{
    /** @var TransactionEndpoint */
    public $transaction;

    /** @var TokenEndpoint */
    public $token;

    /** @var KeysEndpoint */
    public $keys;

    /** @var APIServiceInterface */
    private $apiService;

    public function __construct(APIServiceInterface $apiService)
    {
        $this->initializeEndpoints($apiService);
    }

    public function useKeys(string $privateKey, string $publicKey): self
    {
        $apiService = new APIService(new HTTPAdapterPicker());
        $apiService->useKeys($privateKey, $publicKey);

        $this->initializeEndpoints($apiService);

        return $this;
    }

    /**
     * @param APIServiceInterface $apiService
     */
    private function initializeEndpoints(APIServiceInterface $apiService): void
    {
        $this->apiService = $apiService;
        $this->transaction = new TransactionEndpoint($apiService);
        $this->token = new TokenEndpoint($apiService);
        $this->keys = new KeysEndpoint($apiService);
    }

    public static function setIsSandboxMode(bool $isSandboxMode): void
    {
        APIService::setIsSandboxMode($isSandboxMode);
    }

    public static function instance(): self
    {
        return new self(new APIService(new HTTPAdapterPicker()));
    }

    /**
     * @throws InvalidResponseException
     */
    public function checkKeys(?string $privateKey = null, ?string $publicKey = null): bool
    {
        return $this->keys->check($privateKey, $publicKey);
    }

    public function verifyWebhookSignature(string $signature, array $payload): bool
    {
        $expectedSignature = hash_hmac(
            'sha256',
            json_encode($payload),
            $this->apiService->getPrivateKey(),
            false
        );

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * @param string $signature
     * @param array $payload
     * @throws InvalidWebhookSignatureException
     */
    public function validateWebhookSignature(string $signature, array $payload): void
    {
        if ($this->verifyWebhookSignature($signature, $payload) === false) {
            throw new InvalidWebhookSignatureException($payload, $signature);
        }
    }
}
