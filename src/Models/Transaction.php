<?php

namespace YouCan\Pay\Models;

use Carbon\Carbon;
use InvalidArgumentException;

readonly class Transaction
{
    public function __construct(
        private string $id,
        private string $orderId,
        private int $status,
        private string $amount,
        private string $currency,
        private Carbon $createdAt,
        private ?string $baseAmount,
        private ?string $baseCurrency
    ) {}

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

    public function getCreatedAt(): Carbon
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
