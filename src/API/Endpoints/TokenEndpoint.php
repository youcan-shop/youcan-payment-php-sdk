<?php

namespace YouCan\Pay\API\Endpoints;

use YouCan\Pay\API\Exceptions\ServerException;
use YouCan\Pay\API\Exceptions\Token\MissingTokenException;
use YouCan\Pay\API\Exceptions\UnexpectedResultException;
use YouCan\Pay\API\Exceptions\UnsupportedResponseException;
use YouCan\Pay\API\Exceptions\ValidationException;
use YouCan\Pay\API\Response;
use YouCan\Pay\Models\Token;

class TokenEndpoint extends Endpoint
{
    private const BASE_ENDPOINT = 'tokenize';

    public function create(
        string $orderId,
        string $amount,
        string $currency,
        string $customerIP,
        string $successUrl = null,
        string $errorUrl = null,
        array $customerInfo = [],
        array $metadata = []
    ): Token {
        $this->assertPrivateKeyIsSet();

        $response = $this->apiService->post($this->createEndpoint(), [
            'pri_key'     => $this->apiService->getPrivateKey(),
            'amount'      => $amount,
            'currency'    => $currency,
            'order_id'    => $orderId,
            'success_url' => $successUrl,
            'error_url'   => $errorUrl,
            'customer_ip' => $customerIP,
            'customer'    => $customerInfo,
            'metadata'    => $metadata,
        ]);

        $responseData = $response->getResponse();

        $this->assertResponse($response);

        return Token::createFromArray($responseData['token']);
    }

    protected function endpoint(): string
    {
        return self::BASE_ENDPOINT;
    }

    /**
     * @param Response $response
     *
     * @throws MissingTokenException|ValidationException|UnexpectedResultException|ServerException|UnsupportedResponseException
     */
    private function assertResponse(Response $response): void
    {
        if ($response->getStatusCode() === 200) {
            if (!is_array($response->get('token')) || !is_string($response->get('token')['id'])) {
                throw new MissingTokenException(
                    'missing token in response. Please try again or contact support',
                    json_encode($response->getResponse()),
                    $response->getStatusCode(),
                );
            }

            return;
        }

        if ($response->getStatusCode() === 404) {
            if ($response->get('success') === false && is_string($response->get('message'))) {
                throw new ValidationException(
                    (string)$response->get('message'),
                    json_encode($response->getResponse()),
                    $response->getStatusCode(),
                );
            }
        }

        if ($response->getStatusCode() === 422) {
            if ($response->get('success') === false && is_string($response->get('message'))) {
                throw new ValidationException(
                    (string)$response->get('message'),
                    json_encode($response->getResponse()),
                    $response->getStatusCode(),
                );
            }

            throw new UnexpectedResultException(
                'got unexpected result from server. Validation response with wrong payload',
                json_encode($response->getResponse()),
                $response->getStatusCode()
            );
        }

        if ($response->getStatusCode() >= 500 && $response->getStatusCode() < 600) {
            throw new ServerException(
                'internal error from server. Support has been notified. Please try again!',
                json_encode($response->getResponse()),
                $response->getStatusCode(),
            );
        }

        throw new UnsupportedResponseException(
            'not supported status code from the server.',
            json_encode($response->getResponse()),
            $response->getStatusCode(),
        );
    }
}
