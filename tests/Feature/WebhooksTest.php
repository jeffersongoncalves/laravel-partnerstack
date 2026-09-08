<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\PartnerStack\Exceptions\PartnerStackException;
use JeffersonGoncalves\PartnerStack\Facades\PartnerStack;

it('lists webhooks', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/webhooks*' => Http::response(['data' => ['items' => []]], 200),
    ]);

    expect(PartnerStack::listWebhooks())->toBe(['data' => ['items' => []]]);
});

it('gets a webhook', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/webhooks/whk-1' => Http::response(['key' => 'whk-1'], 200),
    ]);

    expect(PartnerStack::getWebhook('whk-1'))->toBe(['key' => 'whk-1']);
});

it('creates a webhook', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/webhooks' => Http::response(['key' => 'whk-2'], 200),
    ]);

    expect(PartnerStack::createWebhook([
        'target' => 'https://example.com/hooks/partnerstack',
        'events' => ['customer.created', 'transaction.created'],
    ]))->toBe(['key' => 'whk-2']);

    Http::assertSent(fn (Request $request) => $request->data()['events'] === ['customer.created', 'transaction.created']);
});

it('deletes a webhook', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/webhooks/whk-1' => Http::response([], 200),
    ]);

    expect(PartnerStack::deleteWebhook('whk-1'))->toBe([]);

    Http::assertSent(fn (Request $request) => $request->method() === 'DELETE');
});

it('throws with the API error message on a failed response', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/webhooks/whk-1' => Http::response(['error' => 'Webhook not found'], 404),
    ]);

    expect(fn () => PartnerStack::getWebhook('whk-1'))
        ->toThrow(PartnerStackException::class, 'Webhook not found');
});

it('falls back to the raw body when the error response has no message', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/webhooks/whk-1' => Http::response('boom', 500),
    ]);

    expect(fn () => PartnerStack::getWebhook('whk-1'))
        ->toThrow(PartnerStackException::class, 'boom');
});

it('carries the HTTP status code on the exception', function () {
    Http::fake([
        'api.partnerstack.com/api/v2/webhooks/whk-1' => Http::response(['message' => 'Unauthorized'], 401),
    ]);

    try {
        PartnerStack::getWebhook('whk-1');
    } catch (PartnerStackException $exception) {
        expect($exception->statusCode)->toBe(401);
    }
});
