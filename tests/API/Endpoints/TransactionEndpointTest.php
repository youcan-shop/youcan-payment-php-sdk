<?php

namespace Tests\API\Endpoints;

use Tests\API\FakeAPIService;
use Tests\BaseTestCase;
use YouCan\Pay\API\Endpoints\TransactionEndpoint;
use YouCan\Pay\API\Response;

class TransactionEndpointTest extends BaseTestCase
{
    public function test_get_transaction_return_success()
    {
        $response = new Response(
            200,
            [
                "id"            => "123",
                "order_id"      => "123",
                "status"        => 1,
                "amount"        => "20.00",
                "currency"      => "USD",
                "base_amount"   => null,
                "base_currency" => null,
                "created_at"    => "2021-08-08 10:00:00"
            ]
        );

        $fakeAPIService = new FakeAPIService($response);

        $transactionEndpoint = new TransactionEndpoint($fakeAPIService);
        $transaction = $transactionEndpoint->get("123");

        $this->assertEquals("123", $transaction->getId());
        $this->assertEquals("123", $transaction->getOrderId());
        $this->assertEquals(1, $transaction->getStatus());
        $this->assertEquals("20.00", $transaction->getAmount());
        $this->assertEquals("USD", $transaction->getCurrency());
        $this->assertEquals("2021-08-08 10:00:00", $transaction->getCreatedAt()->toDateTimeString());
    }

    public function test_get_transaction_return_not_found()
    {
        $response = new Response(404, []);
        $fakeAPIService = new FakeAPIService($response);

        $transactionEndpoint = new TransactionEndpoint($fakeAPIService);
        $transaction = $transactionEndpoint->get("123");

        $this->assertNull($transaction);
    }

    public function test_list_transactions()
    {
        $response = new Response(
            200,
            [
                [
                    "id"            => "123",
                    "order_id"      => "123",
                    "status"        => 1,
                    "amount"        => "20.00",
                    "currency"      => "USD",
                    "base_amount"   => null,
                    "base_currency" => null,
                    "created_at"    => "2021-08-08 10:00:00"
                ],
                [
                    "id"            => "124",
                    "order_id"      => "124",
                    "status"        => 1,
                    "amount"        => "30.00",
                    "currency"      => "MAD",
                    "base_amount"   => null,
                    "base_currency" => null,
                    "created_at"    => "2021-08-18 10:00:00"
                ]
            ]
        );
        $fakeAPIService = new FakeAPIService($response);

        $transactionEndpoint = new TransactionEndpoint($fakeAPIService);
        $transactions = $transactionEndpoint->list();

        $this->assertIsArray($transactions);

        $firstTransaction = $transactions[0];

        $this->assertEquals("123", $firstTransaction->getId());
        $this->assertEquals("123", $firstTransaction->getOrderId());
        $this->assertEquals(1, $firstTransaction->getStatus());
        $this->assertEquals("20.00", $firstTransaction->getAmount());
        $this->assertEquals("USD", $firstTransaction->getCurrency());
        $this->assertEquals("2021-08-08 10:00:00", $firstTransaction->getCreatedAt()->toDateTimeString());

        $secondTransaction = $transactions[1];

        $this->assertEquals("124", $secondTransaction->getId());
        $this->assertEquals("124", $secondTransaction->getOrderId());
        $this->assertEquals(1, $secondTransaction->getStatus());
        $this->assertEquals("30.00", $secondTransaction->getAmount());
        $this->assertEquals("MAD", $secondTransaction->getCurrency());
        $this->assertEquals("2021-08-18 10:00:00", $secondTransaction->getCreatedAt()->toDateTimeString());
    }
}
