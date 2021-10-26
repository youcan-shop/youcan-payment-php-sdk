<?php

namespace YouCan\Pay\Models;

use InvalidArgumentException;
use YouCan\Pay\API\APIService;
use YouCan\Pay\API\HTTPAdapter\HTTPAdapter;

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

    public function getPaymentURL($lang = 'en'): string
    {
        return sprintf("%spayment-form/%s?lang=%s", HTTPAdapter::BASE_APP_URL . (APIService::$isSandboxMode ? 'sandbox/' : ''), $this->getId(), $lang);
    }
}
