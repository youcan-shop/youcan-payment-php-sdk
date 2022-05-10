<p align="center"><a href="https://youcanpay.com" target="_blank"><img src="https://youcanpay.com/images/ycpay-logo.svg" width="400"></a></p>

<p align="center">
<a href="https://youcanpay.com"><img src="https://github.com/NextmediaMa/youcan-payment-php-sdk/actions/workflows/tests.yml/badge.svg" alt="Tests"></a>
<a href="https://packagist.org/packages/youcanpay/payment-sdk"><img src="https://img.shields.io/packagist/dt/youcanpay/payment-sdk" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/youcanpay/payment-sdk"><img src="https://img.shields.io/packagist/v/youcanpay/payment-sdk" alt="Latest Version"></a>
<a href="https://packagist.org/packages/youcanpay/payment-sdk"><img src="https://img.shields.io/packagist/l/youcanpay/payment-sdk" alt="License"></a>
</p>

This package allows the developer to interact easily with the [YouCan Pay API](https://youcanpay.com/docs).

This documentation is separated in two integrations: **Default Integration** and the **Standalone Integration**.
you have the choice to do one integration or both integrations in your checkout page.

## YouCan Pay SDK Setup

Instructions for adding the YouCan Pay SDK to your PHP Applications.

### Step 1. Requirements

- YouCan Pay Account.
- Your YouCan Pay `private_key` and `public_key` available in Settings > API Keys.
- Visual Studio Code or Sublime Text or any IDE.
- Website with SSL, required only if you want to use the **default mode** for your payments.
- Composer installed in your development environment.

### Step 2. Add YouCan Pay SDK

Open your PHP project, add the following.

- Run this command to download and include YouCan Pay PHP SDK in your project.
  If you can't install composer, you can manually download the SDK from GitHub `https://github.com/NextmediaMa/youcan-payment-php-sdk/archive/refs/heads/master.zip`.

```bash
composer require youcanpay/payment-sdk
```

### Step 2.1 YouCan Pay: Default Integration

The following is a quick guide to get up and started with YouCan Pay JS integration, you can view the full documentation by following [this link](https://youcanpay.com/docs).

You can make payments directly on your site, with the possibility of choosing the position in the DOM.

If you choose to use JS integration, you must have an SSL certificate to run in production mode.

2.1.1: Copy this JS script between `<head>...</head>`

```html
<script src="https://pay.youcan.shop/js/ycpay.js"></script>
```

2.1.2: Choose where you want to display payment information (Full Name, Card Numbers, CCV...), must be placed between the `<body>...</body>` tags.

```html
<div id="payment-card"></div>
<button id="pay">Pay</button>
```

2.1.3: Add this code just before the end of the `...</body>` tag.

```html
<script type="text/javascript">
  // Create a YouCan Pay instance.
  const ycPay = new YCPay(
    // String public_key (required): Login to your account.
    // Go to Settings and open API Keys and copy your key.
    "public_key",
    // Optional options object
    {
      formContainer: "#payment-card",
      // Defines what language the form should be rendered in, supports EN, AR, FR.
      locale: "en",

      // Whether the integration should run in sandbox (test) mode or live mode.
      isSandbox: false,

      // A DOM selector representing which component errors should be injected into.
      // If you omit this option, you may alternatively handle errors by chaining a .catch()
      // On the pay method.
      errorContainer: "#error-container",
    }
  );

  // Select which gateways to render
  ycPay.renderAvailableGateways(["CashPlus", "CreditCard"]);

  // Alternatively, you may use gateway specific render methods if you only need one.
  ycPay.renderCreditCardForm();
</script>
```

2.1.4: Tokenization step: this token contains all the order information.

```php
<?php

use YouCan\Pay\YouCanPay;

class ExamplePayment
{
    /**
     * Return a token to make payment for an order, this token is required to make payment with JS script.
     *
     * @return string
     */
    public function createToken()
    {
        // Enable sandbox mode, otherwise delete this line.
        YouCanPay::setIsSandboxMode(true);

        // Create a YouCan Pay instance, to retrieve your private and public keys login to your YouCan Pay account
        // and go to Settings and open API Keys.
        $youCanPay = YouCanPay::instance()->useKeys('my-private-key', 'my-public-key');

        // Data of the customer who wishes to make this purchase.
        // Please keep these keys.
        $customerInfo = [
            'name'         => '',
            'address'      => '',
            'zip_code'     => '',
            'city'         => '',
            'state'        => '',
            'country_code' => '',
            'phone'        => '',
            'email'        => '',
        ];

        // You can use it to send data to retrieve after the response or in the webhook.
        $metadata = [
            // Can you insert what you want here...
            //'key' => 'value'
        ];

        // Create the order you want to be paid
        $token = $youCanPay->token->create(
            // String orderId (required): Identifier of the order you want to be paid.
            "order-id",
            // Integer amount (required): The amount, Example: 25 USD is 2500.
            "2000",
            // String currency (required): Uppercase currency.
            "USD",
            // String customerIP (required): Customer Address IP.
            "123.123.123.123",
            // String successUrl (required): This URL is returned when the payment is successfully processed.
            "https://yourdomain.com/orders-status/success",
            // String errorUrl (required): This URL is returned when payment is invalid.
            "https://yourdomain.com/orders-status/error",
            // Array customerInfo (optional): Data of the customer who wishes to make this purchase.
            $customerInfo,
            // Array metadata (optional): You can use it to send data to retrieve after the response or in the webhook.
            $metadata
        );

        return $token->getId();
    }
}
```

2.5: Retrieve the token you created with the SDK `createToken()` and insert it into the JS script, this token which contains all the information concerning this payment.

When the buyer clicks on the Pay button. the JS code below runs, and you receive a **GET** response in `successUrl` or `errorUrl` you defined in the tokenization step.

```html
<script type="text/javascript">
  // Start the payment on button click
  document.getElementById("pay").addEventListener("click", function () {
    // Execute the payment, it is required to put the created token in the tokenization step.
    ycPay
      .pay("<?php createToken(); ?>")
      .then(successCallback)
      .catch(errorCallback);
  });

  function successCallback(transactionId) {
    //your code here
  }

  function errorCallback(errorMessage) {
    //your code here
  }
</script>
```

### Step 2.2 YouCan Pay: Standalone Integration

2.2.1: Tokenization step: this token contains all the order information.

```php
<?php

use YouCan\Pay\YouCanPay;

class ExamplePayment
{
    /**
     * Return a URL to make payment for an order.
     *
     * @return string
     */
    public function createPaymentURL()
    {
        // Enable sandbox mode, otherwise delete this line.
        YouCanPay::setIsSandboxMode(true);

        // Create a YouCan Pay instance, to retrieve your private and public keys login to your YouCan Pay account
        // and go to Settings and open API Keys.
        $youCanPay = YouCanPay::instance()->useKeys('my-private-key', 'my-public-key');

        // Data of the customer who wishes to make this purchase.
        // Please keep these keys.
        $customerInfo = [
            'name'         => '',
            'address'      => '',
            'zip_code'     => '',
            'city'         => '',
            'state'        => '',
            'country_code' => '',
            'phone'        => '',
            'email'        => '',
        ];

        // You can use it to send data to retrieve after the response or in the webhook.
        $metadata = [
            // Can you insert what you want here...
            //'key' => 'value'
        ];

        // Create the order you want to be paid
        $token = $youCanPay->token->create(
            // String orderId (required): Identifier of the order you want to be paid.
            "order-id",
            // Integer amount (required): The amount, Example: 25 USD is 2500.
            "2000",
            // String currency (required): Uppercase currency.
            "USD",
            // String customerIP (required): Customer Address IP.
            "123.123.123.123",
            // String successUrl (required): This URL is returned when the payment is successfully processed.
            "https://yourdomain.com/orders-status/success",
            // String errorUrl (required): This URL is returned when payment is invalid.
            "https://yourdomain.com/orders-status/error",
            // Array customerInfo (optional): Data of the customer who wishes to make this purchase.
            $customerInfo,
            // Array metadata (optional): You can use it to send data to retrieve after the response or in the webhook.
            $metadata
        );

        return $token->getPaymentURL(
            // lang: Support 3 languages: AR, EN and FR.
            'ar'
        );
    }
}
```

2.2.2: After the tokenization retrieve the link created with `createPaymentURL()` function and integrate it in your DOM or redirect the buyer directly to this URL.

```html
<a href="<?php createPaymentURL(); ?>">Pay Now</a>
```
