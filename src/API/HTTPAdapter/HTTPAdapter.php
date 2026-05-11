<?php

namespace YouCan\Pay\API\HTTPAdapter;

use YouCan\Pay\API\Response;

abstract class HTTPAdapter
{
    public const BASE_APP_URL = 'https://youcanpay.com/';

    private bool $isSandboxMode;

    public function __construct(bool $isSandboxMode)
    {
        $this->isSandboxMode = $isSandboxMode;
    }

    protected function getBaseUrl(): string
    {
        $baseURL = getenv('YOUCAN_PAY_URL') ?: self::BASE_APP_URL;

        return sprintf("%s%s", $baseURL, $this->isSandboxMode ? 'sandbox/api/' : 'api/');
    }

    public function get(string $endpoint, array $params = [], array $headers = []): Response
    {
        return $this->request('GET', $endpoint, $params, $headers);
    }

    public function post(string $endpoint, array $params = [], array $headers = []): Response
    {
        return $this->request('POST', $endpoint, $params, $headers);
    }

    abstract public function request(string $method, string $endpoint, array $params = [], array $headers = []): Response;
}
