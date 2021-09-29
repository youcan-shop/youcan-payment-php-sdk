<?php

namespace YouCan\Pay\Models;

use Carbon\Carbon;
use InvalidArgumentException;

class Transaction
{
    /** @var string */
    private $id;

    /** @var string */
    private $orderId;

    /** @var int */
    private $status;

    /** @var string */
    private $amount;

    /** @var string */
    private $currency;

    /** @var Carbon */
    private $createdAt;

    public function __construct(
        string $id,
        string $orderId,
        int $status,
        string $amount,
        string $currency,
        Carbon $createdAt
    ) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->status = $status;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->createdAt = $createdAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public static function createFromArray(array $attributes): self
    {
        $hasMissingKeys = !isset(
            $attributes['id'],
            $attributes['order_id'],
            $attributes['status'],
            $attributes['amount'],
            $attributes['currency'],
            $attributes['created_at']
        );

        if ($hasMissingKeys) {
            throw new InvalidArgumentException('missing keys in transaction response');
        }

        return new self(
            $attributes['id'],
            $attributes['order_id'],
            (int)$attributes['status'],
            $attributes['amount'],
            $attributes['currency'],
            Carbon::make($attributes['created_at'])
        );
    }
}
