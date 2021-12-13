<p align="center"><a href="https://pay.youcan.shop" target="_blank"><img src="https://pay.youcan.shop/images/ycpay-logo.svg" width="400"></a></p>

<p align="center">
<a href="https://pay.youcan.shop"><img src="https://github.com/NextmediaMa/youcan-payment-php-sdk/actions/workflows/tests.yml/badge.svg" alt="Tests"></a>
<a href="https://packagist.org/packages/youcanpay/payment-sdk"><img src="https://img.shields.io/packagist/dt/youcanpay/payment-sdk" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/youcanpay/payment-sdk"><img src="https://img.shields.io/packagist/v/youcanpay/payment-sdk" alt="Latest Version"></a>
<a href="https://packagist.org/packages/youcanpay/payment-sdk"><img src="https://img.shields.io/packagist/l/youcanpay/payment-sdk" alt="License"></a>
</p>

This package allows the developer to interact easily with the [YouCan Pay API](https://pay.youcan.shop/docs).

## Basic Usage

```bash
composer install youcanpay/payment-sdk
```

```php
use YouCan\Pay\YouCanPay;

$youCanPay = YouCanPay::instance()->useKeys('my-private-key', 'my-public-key');

// check keys are valid
YouCanPay::instance()->checkKeys('private-key', 'public-key');
// or using current instance
$youCanPay->keys->check('private-key', 'public-key');

// generate a token for a new payment
$token = $youCanPay->token->create("order-id", "2000", "USD", "123.123.123.123");
var_dump($token->getToken(), $token->getRedirectURL());

// get details of a transaction
$transaction = $youCanPay->transaction->get('transaction-id');
var_dump($transaction->getAmount(), $transaction->getCurrency());
```

## Using Test, Production environment
You can specify which environment when initializing `YouCanPay` instance

```php
use YouCan\Pay\YouCanPay;

// enable sandbox mode
YouCanPay::setIsSandboxMode(true);
 
$youCanPay = YouCanPay::instance()->useKeys('my-private-key', 'my-public-key');
```
