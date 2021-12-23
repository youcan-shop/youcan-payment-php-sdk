<?php

namespace YouCan\Pay\API\Exceptions;

use Exception;

class InvalidWebhookSignatureException extends Exception
{
    /** @var array  */
    private $payload;

    /** @var string */
    private $signature;

    public function __construct(array $payload, string $signature)
    {
        parent::__construct('invalid webhook signature');

        $this->payload = $payload;
        $this->signature = $signature;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
