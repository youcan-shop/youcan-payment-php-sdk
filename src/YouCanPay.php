<?php

namespace YouCan\Pay;

use YouCan\Pay\API\APIService;
use YouCan\Pay\API\APIServiceInterface;
use YouCan\Pay\API\Endpoints\KeyEndpoint;
use YouCan\Pay\API\Endpoints\TokenEndpoint;
use YouCan\Pay\API\Endpoints\TransactionEndpoint;
use YouCan\Pay\API\HTTPAdapter\HTTPAdapterPicker;

class YouCanPay
{
    /** @var TransactionEndpoint */
    public $transaction;

    /** @var TokenEndpoint */
    public $token;

    /** @var KeyEndpoint */
    public $key;

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
        $this->transaction = new TransactionEndpoint($apiService);
        $this->token = new TokenEndpoint($apiService);
        $this->key = new KeyEndpoint($apiService);
    }

    public static function setIsSandboxMode(bool $isSandboxMode): void
    {
        APIService::setIsSandboxMode($isSandboxMode);
    }

    public static function instance(): self
    {
        return new self(new APIService(new HTTPAdapterPicker()));
    }
}
