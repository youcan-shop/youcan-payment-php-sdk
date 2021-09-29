<?php

namespace YouCan\Pay\API\Endpoints;

use YouCan\Pay\Models\Transaction;

class TransactionEndpoint extends Endpoint
{
    public const BASE_ENDPOINT = 'transactions';

    public function get(string $transactionId): ?Transaction
    {
        $this->assertPrivateKeyIsSet();

        $response = $this->apiService->get($this->singleEndpoint($transactionId), [
            'pri_key' => $this->apiService->getPrivateKey()
        ]);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return Transaction::createFromArray($response->getResponse());
    }

    /**
     * @return Transaction[]
     */
    public function list()
    {
        $this->assertPrivateKeyIsSet();

        $response = $this->apiService->get($this->listEndpoint(), [
            'pri_key' => $this->apiService->getPrivateKey()
        ]);

        $transactions = [];
        foreach ($response->getResponse() as $value) {
            $transactions[] = Transaction::createFromArray((array)$value);
        }

        return $transactions;
    }

    protected function endpoint(): string
    {
        return self::BASE_ENDPOINT;
    }
}
