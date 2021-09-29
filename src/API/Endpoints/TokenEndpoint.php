<?php

namespace YouCan\Pay\API\Endpoints;

use Exception;
use InvalidArgumentException;
use YouCan\Pay\Models\Token;

class TokenEndpoint extends Endpoint
{
    private const BASE_ENDPOINT = 'tokenize';

    public function create(
        string $orderId,
        string $amount,
        string $currency,
        string $customerIP,
        array $customerInfo = []
    ): Token
    {
        $this->assertPrivateKeyIsSet();

        $response = $this->apiService->post($this->createEndpoint(), [
            'pri_key'     => $this->apiService->getPrivateKey(),
            'amount'      => $amount,
            'currency'    => $currency,
            'order_id'    => $orderId,
            'customer_ip' => $customerIP,
            'customer'    => $customerInfo
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception('got unexpected result from server');
        }

        $responseData = $response->getResponse();

        if (!is_array($responseData) || !array_key_exists('token', $responseData)) {
            throw new InvalidArgumentException('missing token in response');
        }

        return Token::createFromArray($responseData['token']);
    }

    protected function endpoint(): string
    {
        return self::BASE_ENDPOINT;
    }
}
