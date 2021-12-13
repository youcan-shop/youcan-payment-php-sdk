<?php

namespace Tests\API\Endpoints;

use Tests\API\FakeAPIService;
use Tests\BaseTestCase;
use YouCan\Pay\API\Endpoints\KeyEndpoint;
use YouCan\Pay\API\Exceptions\InvalidResponseException;
use YouCan\Pay\API\Exceptions\ValidationException;
use YouCan\Pay\API\Response;

class KeyEndpointTest extends BaseTestCase
{
    public function test_check_key_successfully()
    {
        $response = new Response(200, []);
        $fakeAPIService = new FakeAPIService($response);

        $keyEndpoint = new KeyEndpoint($fakeAPIService);
        $result = $keyEndpoint->check($fakeAPIService->getPrivateKey(), $fakeAPIService->getPublicKey());

        $this->assertTrue($result);
    }

    protected function setUp()
    {
        parent::setUp();
    }
}