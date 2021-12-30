<?php

namespace YouCan\Pay\API\HTTPAdapter;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use YouCan\Pay\API\Exceptions\InvalidResponseException;
use YouCan\Pay\API\Response;

class Guzzle67HTTPAdapter extends HTTPAdapter
{
    /** @var GuzzleClient */
    private $httpClient;

    public function __construct(bool $isSandboxMode, array $clientConfig = [])
    {
        parent::__construct($isSandboxMode);

        $this->httpClient = new GuzzleClient(array_merge($clientConfig, [
            'base_uri'                  => $this->getBaseUrl(),
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS     => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]));
    }

    public function request(string $method, string $endpoint, array $params = [], array $headers = []): Response
    {
        $response = $this->httpClient->request($method, $endpoint, [
            RequestOptions::FORM_PARAMS => $params,
            RequestOptions::QUERY => $params,
            RequestOptions::HEADERS => $headers,
        ]);

        $this->assertSuccessResponsePayload($response);

        $responseBody = json_decode((string)$response->getBody(), true);

        return new Response($response->getStatusCode(), is_array($responseBody) ? $responseBody : []);
    }

    private function assertSuccessResponsePayload(ResponseInterface $response): void
    {
        $responseBody = json_decode((string)$response->getBody(), true);

        $successResponseWithWrongBody = $response->getStatusCode() === 200 && !is_array($responseBody);
        if ($successResponseWithWrongBody) {
            throw new InvalidResponseException($response->getStatusCode(), (string)$response->getBody());
        }
    }
}
