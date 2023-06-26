<?php

namespace YouCan\Pay\API\Endpoints;

use YouCan\Pay\API\Exceptions\InvalidResponseException;
use YouCan\Pay\API\Exceptions\ServerException;
use YouCan\Pay\API\Response;

class KeysEndpoint extends Endpoint
{
    private const BASE_ENDPOINT = 'keys';

    /**
     * @param string|null $privateKey
     * @param string|null $publicKey
     * @return bool
     * @throws ServerException
     */
    public function check(?string $privateKey = null, ?string $publicKey = null): bool
    {
        if ($privateKey === null && $publicKey === null) {
            return false;
        }

        $response = $this->apiService->post(
            sprintf('%s/check', self::BASE_ENDPOINT),
            [
                'pri_key'     => $privateKey,
                'pub_key'     => $publicKey,
            ]
        );

        return $this->assertResponse($response);
    }

    protected function endpoint(): string
    {
        return self::BASE_ENDPOINT;
    }

    /**
     * @param Response $response
     * @return bool
     * @throws ServerException
     */
    private function assertResponse(Response $response): bool
    {
        if ($response->getStatusCode() === 200) {
            return true;
        }

        if ($response->getStatusCode() >= 500 && $response->getStatusCode() < 600) {
            throw new ServerException(
                'internal error from server. Support has been notified. Please try again!',
                json_encode($response->getResponse()),
                $response->getStatusCode(),
            );
        }

        return false;
    }
}