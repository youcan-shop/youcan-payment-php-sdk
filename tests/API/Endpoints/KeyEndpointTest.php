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
    public function test_check_keys_success()
    {
        $response = new Response(200, []);
        $fakeAPIService = new FakeAPIService($response);

        $keyEndpoint = new KeyEndpoint($fakeAPIService);
        $result = $keyEndpoint->check("pri_key_123", "pub_key_123");

        $this->assertTrue($result);
    }

    public function test_check_not_found_response_when_keys_not_correct()
    {
        $response = new Response(404, []);

        $fakeAPIService = new FakeAPIService($response);

        $keyEndpoint = new KeyEndpoint($fakeAPIService);
        $result = $keyEndpoint->check("pri_key_123", "pub_key_123");

        $this->assertFalse($result);
    }

    public function test_check_not_found_response_when_no_keys_passed()
    {
        $response = new Response(404, []);
        $fakeAPIService = new FakeAPIService($response);

        $keyEndpoint = new KeyEndpoint($fakeAPIService);
        $result = $keyEndpoint->check();

        $this->assertFalse($result);
    }

    protected function setUp()
    {
        parent::setUp();
    }
}