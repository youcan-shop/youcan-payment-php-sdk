<?php

namespace YouCan\Pay\API\Endpoints;

use YouCan\Pay\API\Exceptions\InvalidResponseException;
use YouCan\Pay\API\Exceptions\ValidationException;
use YouCan\Pay\API\Response;

class KeyEndpoint extends Endpoint
{
    private const BASE_ENDPOINT = 'keys';

    /**
     * @throws InvalidResponseException
     */
    public function check(?string $privateKey = null, ?string $publicKey = null): bool
    {
        $response = $this->apiService->post(
            sprintf('%s/check', $this->createEndpoint()),
            [
                'pri_key'     => $privateKey ?? $this->apiService->getPrivateKey(),
                'pub_key'     => $publicKey ?? $this->apiService->getPublicKey(),
            ]
        );

        return $this->assertResponse($response);
    }

    protected function endpoint(): string
    {
        return self::BASE_ENDPOINT;
    }

    /**
     * @throws ValidationException|InvalidResponseException
     */
    private function assertResponse(Response $response): bool
    {
        if ($response->getStatusCode() === 200) {
            return true;
        }

        if ($response->getStatusCode() === 422) {
            throw new ValidationException('account public key or private key is required');
        }

        if ($response->getStatusCode() >= 500 && $response->getStatusCode() < 600) {
            throw new InvalidResponseException(
                $response->getStatusCode(),
                json_encode($response->getResponse()),
                'internal error from server. Support has been notified. Please try again!'
            );
        }

        return false;
    }
}