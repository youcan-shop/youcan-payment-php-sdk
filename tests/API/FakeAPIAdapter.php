<?php

namespace Tests\API;

use YouCan\Pay\API\HTTPAdapter\HTTPAdapter;
use YouCan\Pay\API\Response;

class FakeAPIAdapter extends HTTPAdapter
{
    /** @var Response */
    private $fakeResponse;

    public function setFakeResponse(Response $response): self
    {
        $this->fakeResponse = $response;

        return $this;
    }

    public function request(string $method, string $endpoint, array $params = [], array $headers = []): Response
    {
        return $this->fakeResponse;
    }
}
