<?php

namespace Tests\Webhook;

use Tests\BaseTestCase;
use YouCan\Pay\API\Exceptions\InvalidWebhookSignatureException;
use YouCan\Pay\YouCanPay;

class WebhookTest extends BaseTestCase
{
    public function test_valid_signature_check()
    {
        $youcanPay = YouCanPay::instance();

        $privateKey = 'pri_t0talIy-l9g1t-aNd-Va|1d-UU1D';
        $payload = $this->getWebhookPayload();

        $signature = hash_hmac(
            'sha256',
            json_encode($payload),
            $privateKey,
            false
        );

        $result = $youcanPay->verifyWebhookSignature($signature, $payload, $privateKey);

        $this->assertTrue($result);
    }

    public function test_invalid_signature_check()
    {
        $youcanPay = YouCanPay::instance();

        $privateKey = 'pri_t0talIy-l9g1t-aNd-Va|1d-UU1D';
        $payload = $this->getWebhookPayload();

        $signature = hash_hmac(
            'sha256',
            json_encode($payload),
            $privateKey,
            false
        );

        $result = $youcanPay->verifyWebhookSignature($signature, $payload, 'invalid_private_key');

        $this->assertFalse($result);
    }

    public function test_it_throws_exception_on_invalid_signature()
    {
        $this->expectException(InvalidWebhookSignatureException::class);

        $youcanPay = YouCanPay::instance();

        $privateKey = 'pri_t0talIy-l9g1t-aNd-Va|1d-UU1D';
        $payload = $this->getWebhookPayload();

        $signature = hash_hmac(
            'sha256',
            json_encode($payload),
            $privateKey,
            false
        );

        $youcanPay->validateWebhookSignature($signature, $payload, 'invalid_private_key');
    }

    private function getWebhookPayload(): array
    {
        return [
            "event_id"      => 9999,
            "event_name"    => "transaction.success",
            "payload"       => [
                "transaction"   => [
                    "id"        => "transaction_id_123",
                    "status"    => 9001,
                    "order_id"  => "order_id_123",
                    "amount"    => "100.00",
                    "currency"  => "MAD",
                ],
                "metadata"      => [
                    "custom_field_1" => "custom_value_1",
                    "custom_field_2" => false,
                    "custom_field_3" => [
                        "custom_value_3_1",
                        "custom_value_3_2",
                        "custom_value_3_3",
                    ],
                ],
            ]
        ];
    }
}