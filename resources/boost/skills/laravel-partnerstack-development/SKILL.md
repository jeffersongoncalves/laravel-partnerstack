---
name: laravel-partnerstack-development
description: Build and work with the Laravel PartnerStack package, covering partnerships, customers, transactions, deals, actions, rewards, leads, groups and webhooks.
---

# Laravel PartnerStack Development

## When to use this skill

Use this skill when:
- Integrating the PartnerStack partner/affiliate program API into a Laravel app
- Adding new PartnerStack endpoints to this package
- Handling PartnerStack API errors

## Core Concepts

### The `PartnerStack` client

`JeffersonGoncalves\PartnerStack\PartnerStack` is a thin wrapper around Laravel's `Http` facade. It is registered as a singleton and resolved via the `PartnerStack` facade (`JeffersonGoncalves\PartnerStack\Facades\PartnerStack`). Every public method maps 1:1 to a PartnerStack v2 endpoint and returns the decoded JSON body as an array — no DTOs.

```php
use JeffersonGoncalves\PartnerStack\Facades\PartnerStack;

PartnerStack::getPartnership('partnership-key');
```

### Authentication

Every request is authenticated with `Http::withBasicAuth(config('partnerstack.public_key'), config('partnerstack.secret_key'))`, sent as `Authorization: Basic base64(public:secret)`. The base URL comes from `config('partnerstack.base_url')`, defaulting to `https://api.partnerstack.com/api/v2`.

### Pagination

The API uses cursor pagination. Every `list*()` method takes an optional query array supporting `limit`, `starting_after`, `ending_before` and `order_by`:

```php
PartnerStack::listCustomers(['limit' => 50, 'starting_after' => 'cus-100']);
```

### Money fields

`amount` on transactions and rewards is an integer in cents (`4990` = $49.90). Never pass a float.

### Error handling

A non-2xx response throws `JeffersonGoncalves\PartnerStack\Exceptions\PartnerStackException`, carrying:
- `getMessage()` — the API's `message` or `error` field, or the raw response body as a fallback
- `statusCode` (public readonly int) — the HTTP status code

```php
try {
    PartnerStack::createTransaction(['customer_key' => 'cus-1', 'amount' => 4990]);
} catch (\JeffersonGoncalves\PartnerStack\Exceptions\PartnerStackException $e) {
    report($e);
}
```

## Common Patterns

### Adding a new endpoint

1. Add a public method to `src/PartnerStack.php` calling the private `get()`/`post()`/`patch()`/`delete()` helpers.
2. Add a Feature test under `tests/Feature/` using `Http::fake()`.
3. Document the method in `README.md` under "Usage" and in this skill's API Reference.

```php
public function newEndpoint(string $key): array
{
    return $this->get("/new-endpoint/{$key}");
}
```

### Recording a referred sale end to end

```php
$customer = PartnerStack::createCustomer([
    'email' => 'john@example.com',
    'partner_key' => 'prt-1',
]);

PartnerStack::createTransaction([
    'customer_key' => $customer['data']['key'],
    'amount' => 4990,
    'currency' => 'usd',
]);
```

## Troubleshooting

### Error: `PartnerStackException` with status 401

**Cause**: The public/secret key pair is missing or wrong — PartnerStack rejects the Basic credentials.

**Solution**: Confirm `PARTNERSTACK_PUBLIC_KEY` and `PARTNERSTACK_SECRET_KEY` are both set and belong to the same PartnerStack account (Settings > Integrations > API keys).

### Error: `PartnerStackException` with a generic status message

**Cause**: The API returned a non-2xx response without a `message` or `error` field in the JSON body.

**Solution**: Inspect `$e->statusCode` and the raw response — the fallback message is just the raw response body.

## API Reference

All methods return `array<string, mixed>`. `$query` on every `list*()` method accepts `limit`, `starting_after`, `ending_before`, `order_by`.

### Partnerships

| Method | Notes |
|--------|-------|
| `listPartnerships(array $query = [])` | |
| `getPartnership(string $key)` | |
| `createPartnership(array $data)` | `email` and `group_key` required; `name`, `first_name`, `last_name` optional |
| `updatePartnership(string $key, array $data)` | `name`, `group_key` |

### Customers

| Method | Notes |
|--------|-------|
| `listCustomers(array $query = [])` | |
| `getCustomer(string $key)` | |
| `createCustomer(array $data)` | `email` and `partner_key` required; `name`, `customer_key` optional |
| `updateCustomer(string $key, array $data)` | `name`, `email`, `partner_key` |
| `deleteCustomer(string $key)` | |

### Transactions

| Method | Notes |
|--------|-------|
| `listTransactions(array $query = [])` | |
| `getTransaction(string $key)` | |
| `createTransaction(array $data)` | `customer_key` and `amount` (cents) required; `currency`, `category`, `product_key` optional |
| `deleteTransaction(string $key)` | |

### Deals

| Method | Notes |
|--------|-------|
| `listDeals(array $query = [])` | |
| `getDeal(string $key)` | |
| `createDeal(array $data)` | `partner_key` and `name` required; `amount`, `currency`, `stage` optional |
| `updateDeal(string $key, array $data)` | `name`, `amount`, `stage`, `status` |
| `archiveDeal(string $key)` | |

### Actions

| Method | Notes |
|--------|-------|
| `listActions(array $query = [])` | |
| `createAction(array $data)` | `customer_key` and `key` required; `value` optional |

### Rewards

| Method | Notes |
|--------|-------|
| `listRewards(array $query = [])` | |
| `createReward(array $data)` | `partner_key` and `amount` (cents) required; `description`, `currency` optional |

### Leads

| Method | Notes |
|--------|-------|
| `listLeads(array $query = [])` | |
| `getLead(string $key)` | |
| `createLead(array $data)` | `partner_key` and `email` required; `name`, `company` optional |
| `updateLead(string $key, array $data)` | `email`, `name`, `status` |

### Groups

| Method | Notes |
|--------|-------|
| `listGroups(array $query = [])` | |

### Webhooks

| Method | Notes |
|--------|-------|
| `listWebhooks(array $query = [])` | |
| `getWebhook(string $key)` | |
| `createWebhook(array $data)` | `target` required; `events` optional list of event names |
| `deleteWebhook(string $key)` | |
