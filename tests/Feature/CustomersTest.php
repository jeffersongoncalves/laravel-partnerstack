<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\PartnerStack\Facades\PartnerStack;

it('lists customers', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/customers*' => Http::response(['data' => ['items' => []]], 200),
    ]);

    expect(PartnerStack::listCustomers())->toBe(['data' => ['items' => []]]);
});

it('gets a customer', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/customers/cus-1' => Http::response(['key' => 'cus-1'], 200),
    ]);

    expect(PartnerStack::getCustomer('cus-1'))->toBe(['key' => 'cus-1']);
});

it('creates a customer', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/customers' => Http::response(['key' => 'cus-2'], 200),
    ]);

    expect(PartnerStack::createCustomer([
        'email' => 'john@example.com',
        'partner_key' => 'prt-1',
    ]))->toBe(['key' => 'cus-2']);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->data()['partner_key'] === 'prt-1');
});

it('updates a customer', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/customers/cus-1' => Http::response(['key' => 'cus-1'], 200),
    ]);

    expect(PartnerStack::updateCustomer('cus-1', ['name' => 'John Doe']))->toBe(['key' => 'cus-1']);

    Http::assertSent(fn (Request $request) => $request->method() === 'PATCH');
});

it('deletes a customer', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/customers/cus-1' => Http::response([], 200),
    ]);

    expect(PartnerStack::deleteCustomer('cus-1'))->toBe([]);

    Http::assertSent(fn (Request $request) => $request->method() === 'DELETE');
});
