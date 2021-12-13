<?php

namespace Tests\API\Endpoints;

use Tests\API\FakeAPIService;
use Tests\BaseTestCase;
use YouCan\Pay\API\Endpoints\KeysEndpoint;
use YouCan\Pay\API\Response;

class KeysEndpointTest extends BaseTestCase
{
    public function test_check_keys_success()
    {
        $response = new Response(200, []);
        $fakeAPIService = new FakeAPIService($response);

        $keysEndpoint = new KeysEndpoint($fakeAPIService);
        $result = $keysEndpoint->check("pri_key_123", "pub_key_123");

        $this->assertTrue($result);
    }

    public function test_check_not_found_response_when_keys_not_correct()
    {
        $response = new Response(404, []);

        $fakeAPIService = new FakeAPIService($response);

        $keysEndpoint = new KeysEndpoint($fakeAPIService);
        $result = $keysEndpoint->check("pri_key_123", "pub_key_123");

        $this->assertFalse($result);
    }

    public function test_check_not_found_response_when_no_keys_passed()
    {
        $response = new Response(404, []);
        $fakeAPIService = new FakeAPIService($response);

        $keysEndpoint = new KeysEndpoint($fakeAPIService);
        $result = $keysEndpoint->check();

        $this->assertFalse($result);
    }

    protected function setUp()
    {
        parent::setUp();
    }
}