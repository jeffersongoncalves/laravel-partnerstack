<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\PartnerStack\Facades\PartnerStack;

it('lists partnerships', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/partnerships*' => Http::response(['data' => ['items' => []]], 200),
    ]);

    expect(PartnerStack::listPartnerships(['limit' => 10]))->toBe(['data' => ['items' => []]]);

    Http::assertSent(fn (Request $request) => str_contains($request->url(), 'limit=10'));
});

it('authenticates with basic credentials', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/partnerships*' => Http::response([], 200),
    ]);

    PartnerStack::listPartnerships();

    Http::assertSent(fn (Request $request) => $request->header('Authorization')[0]
        === 'Basic '.base64_encode('fake-public-key:fake-secret-key'));
});

it('gets a partnership', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/partnerships/prt-1' => Http::response(['key' => 'prt-1'], 200),
    ]);

    expect(PartnerStack::getPartnership('prt-1'))->toBe(['key' => 'prt-1']);
});

it('creates a partnership', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/partnerships' => Http::response(['key' => 'prt-2'], 200),
    ]);

    expect(PartnerStack::createPartnership([
        'email' => 'jane@example.com',
        'group_key' => 'grp-1',
    ]))->toBe(['key' => 'prt-2']);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->data()['email'] === 'jane@example.com'
        && $request->data()['group_key'] === 'grp-1');
});

it('updates a partnership', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/partnerships/prt-1' => Http::response(['key' => 'prt-1'], 200),
    ]);

    expect(PartnerStack::updatePartnership('prt-1', ['name' => 'Jane Doe']))->toBe(['key' => 'prt-1']);

    Http::assertSent(fn (Request $request) => $request->method() === 'PATCH'
        && $request->data()['name'] === 'Jane Doe');
});

it('lists groups', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/groups*' => Http::response(['data' => ['items' => []]], 200),
    ]);

    expect(PartnerStack::listGroups())->toBe(['data' => ['items' => []]]);
});
