<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\PartnerStack\Facades\PartnerStack;

it('lists transactions', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/transactions*' => Http::response(['data' => ['items' => []]], 200),
    ]);

    expect(PartnerStack::listTransactions())->toBe(['data' => ['items' => []]]);
});

it('gets a transaction', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/transactions/txn-1' => Http::response(['key' => 'txn-1'], 200),
    ]);

    expect(PartnerStack::getTransaction('txn-1'))->toBe(['key' => 'txn-1']);
});

it('creates a transaction with the amount in cents', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/transactions' => Http::response(['key' => 'txn-2'], 200),
    ]);

    expect(PartnerStack::createTransaction([
        'customer_key' => 'cus-1',
        'amount' => 4990,
        'currency' => 'usd',
    ]))->toBe(['key' => 'txn-2']);

    Http::assertSent(fn (Request $request) => $request->data()['amount'] === 4990);
});

it('deletes a transaction', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/transactions/txn-1' => Http::response([], 200),
    ]);

    expect(PartnerStack::deleteTransaction('txn-1'))->toBe([]);

    Http::assertSent(fn (Request $request) => $request->method() === 'DELETE');
});

it('lists and creates rewards', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/rewards*' => Http::response(['data' => ['items' => []]], 200),
    ]);

    expect(PartnerStack::listRewards())->toBe(['data' => ['items' => []]]);
    expect(PartnerStack::createReward(['partner_key' => 'prt-1', 'amount' => 1000]))
        ->toBe(['data' => ['items' => []]]);
});

it('lists and creates actions', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/actions*' => Http::response(['data' => ['items' => []]], 200),
    ]);

    expect(PartnerStack::listActions())->toBe(['data' => ['items' => []]]);
    expect(PartnerStack::createAction(['customer_key' => 'cus-1', 'key' => 'signup']))
        ->toBe(['data' => ['items' => []]]);
});
