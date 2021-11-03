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

        $this->assertEquals($transaction->getId(), "123");
        $this->assertEquals($transaction->getOrderId(), "123");
        $this->assertEquals($transaction->getStatus(), 1);
        $this->assertEquals($transaction->getAmount(), "20.00");
        $this->assertEquals($transaction->getCurrency(), "USD");
        $this->assertEquals($transaction->getCreatedAt(), "2021-08-08 10:00:00");
    }

    public function test_get_transaction_return_not_found()
    {
        $response = new Response(
            404,
            []
        );
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

        $this->assertEquals($firstTransaction->getId(), "123");
        $this->assertEquals($firstTransaction->getOrderId(), "123");
        $this->assertEquals($firstTransaction->getStatus(), 1);
        $this->assertEquals($firstTransaction->getAmount(), "20.00");
        $this->assertEquals($firstTransaction->getCurrency(), "USD");
        $this->assertEquals($firstTransaction->getCreatedAt(), "2021-08-08 10:00:00");

        $secondTransaction = $transactions[1];

        $this->assertEquals($secondTransaction->getId(), "124");
        $this->assertEquals($secondTransaction->getOrderId(), "124");
        $this->assertEquals($secondTransaction->getStatus(), 1);
        $this->assertEquals($secondTransaction->getAmount(), "30.00");
        $this->assertEquals($secondTransaction->getCurrency(), "MAD");
        $this->assertEquals($secondTransaction->getCreatedAt(), "2021-08-18 10:00:00");
    }

    protected function setUp()
    {
        parent::setUp();
    }
}
