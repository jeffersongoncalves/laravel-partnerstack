<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\PartnerStack\Facades\PartnerStack;

it('lists deals', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/deals*' => Http::response(['data' => ['items' => []]], 200),
    ]);

    expect(PartnerStack::listDeals())->toBe(['data' => ['items' => []]]);
});

it('gets a deal', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/deals/dea-1' => Http::response(['key' => 'dea-1'], 200),
    ]);

    expect(PartnerStack::getDeal('dea-1'))->toBe(['key' => 'dea-1']);
});

it('creates a deal', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/deals' => Http::response(['key' => 'dea-2'], 200),
    ]);

    expect(PartnerStack::createDeal([
        'partner_key' => 'prt-1',
        'name' => 'Acme Corp',
        'amount' => 250000,
    ]))->toBe(['key' => 'dea-2']);

    Http::assertSent(fn (Request $request) => $request->data()['name'] === 'Acme Corp');
});

it('updates a deal', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/deals/dea-1' => Http::response(['key' => 'dea-1'], 200),
    ]);

    expect(PartnerStack::updateDeal('dea-1', ['stage' => 'won']))->toBe(['key' => 'dea-1']);

    Http::assertSent(fn (Request $request) => $request->method() === 'PATCH');
});

it('archives a deal', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/deals/dea-1' => Http::response([], 200),
    ]);

    expect(PartnerStack::archiveDeal('dea-1'))->toBe([]);

    Http::assertSent(fn (Request $request) => $request->method() === 'DELETE');
});

it('lists, gets, creates and updates leads', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/leads/led-1' => Http::response(['key' => 'led-1'], 200),
        'api.partnerstack.com/api/v2/leads*' => Http::response(['data' => ['items' => []]], 200),
    ]);

    expect(PartnerStack::listLeads())->toBe(['data' => ['items' => []]]);
    expect(PartnerStack::getLead('led-1'))->toBe(['key' => 'led-1']);
    expect(PartnerStack::createLead(['partner_key' => 'prt-1', 'email' => 'lead@example.com']))
        ->toBe(['data' => ['items' => []]]);
    expect(PartnerStack::updateLead('led-1', ['status' => 'qualified']))->toBe(['key' => 'led-1']);
});
