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

    /** @var ?string */
    private $baseAmount;

    /** @var ?string */
    private $baseCurrency;

    /** @var Carbon */
    private $createdAt;

    public function __construct(
        string $id,
        string $orderId,
        int $status,
        string $amount,
        string $currency,
        Carbon $createdAt,
        ?string $baseAmount,
        ?string $baseCurrency
    ) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->status = $status;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->createdAt = $createdAt;
        $this->baseAmount = $baseAmount;
        $this->baseCurrency = $baseCurrency;
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

    public function getBaseAmount(): ?string
    {
        return $this->baseAmount;
    }

    public function getBaseCurrency(): ?string
    {
        return $this->baseCurrency;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public static function createFromArray(array $attributes): self
    {
        $hasMissingKeys = count(
                array_diff([
                    'id',
                    'order_id',
                    'status',
                    'amount',
                    'currency',
                    'base_currency',
                    'base_amount',
                    'created_at'
                ],
                    array_keys($attributes)
                )
            ) !== 0;

        if ($hasMissingKeys) {
            throw new InvalidArgumentException('missing keys in transaction response');
        }

        return new self(
            $attributes['id'],
            $attributes['order_id'],
            (int)$attributes['status'],
            $attributes['amount'],
            $attributes['currency'],
            Carbon::make($attributes['created_at']),
            $attributes['base_amount'],
            $attributes['base_currency']
        );
    }
}
