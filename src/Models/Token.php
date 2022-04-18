<?php

namespace YouCan\Pay\Models;

use InvalidArgumentException;
use YouCan\Pay\API\APIService;
use YouCan\Pay\API\HTTPAdapter\HTTPAdapter;

class Token
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public static function createFromArray(array $attributes): self
    {
        $hasMissingKeys = !isset(
            $attributes['id']
        );

        if ($hasMissingKeys) {
            throw new InvalidArgumentException('missing keys in token response');
        }

        return new self($attributes['id']);
    }

    public function getPaymentURL($lang = 'en'): string
    {
        return sprintf(
            "%spayment-form/%s?lang=%s",
            HTTPAdapter::BASE_APP_URL . (APIService::$isSandboxMode ? 'sandbox/' : ''),
            $this->getId(),
            $lang
        );
    }
}
