<?php

namespace YouCan\Pay\API\HTTPAdapter;

use YouCan\Pay\API\Exceptions\InvalidResponseException;
use YouCan\Pay\API\Response;

class CurlHTTPAdapter extends HTTPAdapter
{
    /**
     * Default response timeout (in seconds).
     */
    const DEFAULT_TIMEOUT = 10;

    /**
     * Default connect timeout (in seconds).
     */
    const DEFAULT_CONNECT_TIMEOUT = 2;

    public function __construct(bool $isSandboxMode)
    {
        parent::__construct($isSandboxMode);
    }

    protected function parseHeaders($headers)
    {
        $result = [];

        foreach ($headers as $key => $value) {
            $result[] = $key .': ' . $value;
        }

        return $result;
    }

    public function request(string $method, string $endpoint, array $params = [], array $headers = []): Response
    {
        $headers["content-type"] = "application/json";
        $headers["Accept"] = "application/json";
        $method = strtolower($method);

        if ($method === 'post') {
            $curl = curl_init(sprintf('%s%s', $this->getBaseUrl(), $endpoint));
        } else {
            $curl = curl_init(sprintf('%s%s?%s', $this->getBaseUrl(), $endpoint, http_build_query($params)));
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->parseHeaders($headers));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, self::DEFAULT_CONNECT_TIMEOUT);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::DEFAULT_TIMEOUT);

        switch ($method) {
            case 'post':
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));

                break;
            case 'get':
                break;
            default:
                throw new \InvalidArgumentException("Invalid http method: ". $method);
        }

        $response = curl_exec($curl);

        $statusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        if ($response === false) {
            throw new InvalidResponseException($statusCode, "Curl error: " . curl_error($curl));
        }

        curl_close($curl);

        $responseBody = json_decode((string)$response, true);

        return new Response($statusCode, is_array($responseBody) ? $responseBody : []);
    }
}
