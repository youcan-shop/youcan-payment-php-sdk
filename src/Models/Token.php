<?php

namespace YouCan\Pay\Models;

use InvalidArgumentException;

class Token
{
    /** @var string */
    private $id;

    /** @var string */
    private $transactionId;

    public function __construct(string $id, string $transactionId)
    {
        $this->id = $id;
        $this->transactionId = $transactionId;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public static function createFromArray(array $attributes)
    {
        $hasMissingKeys = !isset(
            $attributes['id'],
            $attributes['transaction_id']
        );

        if ($hasMissingKeys) {
            throw new InvalidArgumentException('missing keys in token response');
        }

        return new self($attributes['id'], $attributes['transaction_id']);
    }
}
