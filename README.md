# YouCan Pay SDK

[![tests](https://github.com/NextmediaMa/youcan-payment-php-sdk/actions/workflows/tests.yml/badge.svg)](https://github.com/NextmediaMa/youcan-payment-php-sdk/actions/workflows/tests.yml)

This package allows the developer to interact easily with the YouCan Pay API.

## Basic Usage

```bash
composer install youcanpay/payment-sdk
```

```php
use YouCan\Pay\YouCanPay;

$youCanPay = YouCanPay::instance()->useKeys('my-private-key', 'my-public-key');

// generate a token for a new payment
$token = $youCanPay->token->create("order-id", "20.50", "USD", "123.123.123.123");
var_dump($token->getToken(), $token->getRedirectURL());

// get details of a transaction
$transaction = $youCanPay->transaction->get('transaction-id');
var_dump($transaction->getAmount(), $transaction->getCurrency());
```

## Using Test, Production environment
You can specify which environment when initializing `YouCanPay` instance

```php
use YouCan\Pay\YouCanPay;
 
$youCanPay = YouCanPay::instance()->useKeys('my-private-key', 'my-public-key');

// enable sandbox mode
YouCanPay::setIsSandboxMode(true);
```
