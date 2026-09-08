<div class="filament-hidden">

![Laravel PartnerStack](https://raw.githubusercontent.com/jeffersongoncalves/laravel-partnerstack/main/art/jeffersongoncalves-laravel-partnerstack.png)

</div>

# Laravel PartnerStack

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-partnerstack.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-partnerstack)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-partnerstack/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-partnerstack/actions?query=workflow%3Atests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-partnerstack/pint.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-partnerstack/actions?query=workflow%3A%22Fix+PHP+code+styling%22+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-partnerstack.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-partnerstack)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-partnerstack.svg?style=flat-square)](LICENSE.md)

A Laravel client for the [PartnerStack](https://partnerstack.com) partner and affiliate program API. A fluent `PartnerStack` facade covers partnerships, customers, transactions, deals, actions, rewards, leads, groups and webhooks, authenticates every request with your public and secret keys as HTTP Basic credentials, and throws a `PartnerStackException` on a non-2xx response instead of returning a silent error array.

## Features

- **Partnerships** — `listPartnerships()`, `getPartnership()`, `createPartnership()`, `updatePartnership()`
- **Customers** — `listCustomers()`, `getCustomer()`, `createCustomer()`, `updateCustomer()`, `deleteCustomer()`
- **Transactions** — `listTransactions()`, `getTransaction()`, `createTransaction()`, `deleteTransaction()`
- **Deals** — `listDeals()`, `getDeal()`, `createDeal()`, `updateDeal()`, `archiveDeal()`
- **Actions** — `listActions()`, `createAction()`
- **Rewards** — `listRewards()`, `createReward()`
- **Leads** — `listLeads()`, `getLead()`, `createLead()`, `updateLead()`
- **Groups** — `listGroups()`
- **Webhooks** — `listWebhooks()`, `getWebhook()`, `createWebhook()`, `deleteWebhook()`
- **Thin by design** — every method returns the raw decoded JSON response as an array, no DTOs
- **Fails loud** — a non-2xx API response throws `PartnerStackException` carrying the API's error message and HTTP status code

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-partnerstack
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="partnerstack-config"
```

## Configuration

Add to your `.env`:

```env
PARTNERSTACK_PUBLIC_KEY=your-public-key
PARTNERSTACK_SECRET_KEY=your-secret-key
```

Both keys live in your PartnerStack dashboard under **Settings > Integrations > API keys**.

### Config Options

```php
// config/partnerstack.php
return [
    'public_key' => env('PARTNERSTACK_PUBLIC_KEY'),
    'secret_key' => env('PARTNERSTACK_SECRET_KEY'),
    'base_url' => env('PARTNERSTACK_BASE_URL', 'https://api.partnerstack.com/api/v2'),
];
```

## Usage

```php
use JeffersonGoncalves\PartnerStack\Exceptions\PartnerStackException;
use JeffersonGoncalves\PartnerStack\Facades\PartnerStack;
```

Every `list*()` method accepts the API's cursor pagination options — `limit`, `starting_after`, `ending_before` and `order_by`:

```php
PartnerStack::listPartnerships(['limit' => 50, 'order_by' => 'created_at']);
```

### Partnerships

```php
PartnerStack::listPartnerships();
PartnerStack::getPartnership('partnership-key');

PartnerStack::createPartnership([
    'email' => 'jane@example.com',
    'group_key' => 'group-key',
    'first_name' => 'Jane',
    'last_name' => 'Doe',
]);

PartnerStack::updatePartnership('partnership-key', ['name' => 'Jane Doe']);
```

### Customers

```php
PartnerStack::listCustomers();
PartnerStack::getCustomer('customer-key');

PartnerStack::createCustomer([
    'email' => 'john@example.com',
    'partner_key' => 'partner-key',
    'customer_key' => 'your-internal-id',
]);

PartnerStack::updateCustomer('customer-key', ['name' => 'John Doe']);
PartnerStack::deleteCustomer('customer-key');
```

### Transactions

Amounts are in cents.

```php
PartnerStack::listTransactions();
PartnerStack::getTransaction('transaction-key');

PartnerStack::createTransaction([
    'customer_key' => 'customer-key',
    'amount' => 4990,
    'currency' => 'usd',
    'category' => 'subscription',
]);

PartnerStack::deleteTransaction('transaction-key');
```

### Deals

```php
PartnerStack::listDeals();
PartnerStack::getDeal('deal-key');

PartnerStack::createDeal([
    'partner_key' => 'partner-key',
    'name' => 'Acme Corp',
    'amount' => 250000,
    'stage' => 'proposal',
]);

PartnerStack::updateDeal('deal-key', ['stage' => 'won', 'status' => 'closed']);
PartnerStack::archiveDeal('deal-key');
```

### Actions

```php
PartnerStack::listActions();

PartnerStack::createAction([
    'customer_key' => 'customer-key',
    'key' => 'signup',
    'value' => 1,
]);
```

### Rewards

Amounts are in cents.

```php
PartnerStack::listRewards();

PartnerStack::createReward([
    'partner_key' => 'partner-key',
    'amount' => 10000,
    'description' => 'Q1 bonus',
    'currency' => 'usd',
]);
```

### Leads

```php
PartnerStack::listLeads();
PartnerStack::getLead('lead-key');

PartnerStack::createLead([
    'partner_key' => 'partner-key',
    'email' => 'lead@example.com',
    'company' => 'Acme Corp',
]);

PartnerStack::updateLead('lead-key', ['status' => 'qualified']);
```

### Groups

```php
PartnerStack::listGroups();
```

### Webhooks

```php
PartnerStack::listWebhooks();
PartnerStack::getWebhook('webhook-key');

PartnerStack::createWebhook([
    'target' => 'https://example.com/hooks/partnerstack',
    'events' => ['customer.created', 'transaction.created'],
]);

PartnerStack::deleteWebhook('webhook-key');
```

### Handling errors

```php
try {
    $partnership = PartnerStack::getPartnership('partnership-key');
} catch (PartnerStackException $e) {
    // $e->getMessage()  — the API's error message, or the raw response body
    // $e->statusCode    — the HTTP status code returned by PartnerStack
}
```

## Testing

```bash
composer test
```

## Static Analysis

```bash
composer analyse
```

## Code Formatting

```bash
composer format
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Jefferson Simão Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
