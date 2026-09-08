## Laravel PartnerStack

This package provides a fluent `PartnerStack` facade for the [PartnerStack](https://partnerstack.com) partner and affiliate program API: partnerships, customers, transactions, deals, actions, rewards, leads, groups and webhooks.

### Installation

@verbatim
<code-snippet name="Install the package" lang="bash">
composer require jeffersongoncalves/laravel-partnerstack
</code-snippet>
@endverbatim

Set `PARTNERSTACK_PUBLIC_KEY` and `PARTNERSTACK_SECRET_KEY` in `.env`. Optionally override `PARTNERSTACK_BASE_URL`.

### Features

- **Partnerships**: `listPartnerships()`, `getPartnership()`, `createPartnership()`, `updatePartnership()`.
- **Customers**: `listCustomers()`, `getCustomer()`, `createCustomer()`, `updateCustomer()`, `deleteCustomer()`.
- **Transactions**: `listTransactions()`, `getTransaction()`, `createTransaction()`, `deleteTransaction()` — amounts in cents.
- **Deals**: `listDeals()`, `getDeal()`, `createDeal()`, `updateDeal()`, `archiveDeal()`.
- **Actions**: `listActions()`, `createAction()`.
- **Rewards**: `listRewards()`, `createReward()` — amounts in cents.
- **Leads**: `listLeads()`, `getLead()`, `createLead()`, `updateLead()`.
- **Groups**: `listGroups()`.
- **Webhooks**: `listWebhooks()`, `getWebhook()`, `createWebhook()`, `deleteWebhook()`.

@verbatim
<code-snippet name="Create a customer and a transaction" lang="php">
use JeffersonGoncalves\PartnerStack\Facades\PartnerStack;

PartnerStack::createCustomer([
    'email' => 'john@example.com',
    'partner_key' => 'partner-key',
]);

PartnerStack::createTransaction([
    'customer_key' => 'customer-key',
    'amount' => 4990,
    'currency' => 'usd',
]);
</code-snippet>
@endverbatim

### Configuration

@verbatim
<code-snippet name="Config example" lang="php">
// config/partnerstack.php
return [
    'public_key' => env('PARTNERSTACK_PUBLIC_KEY'),
    'secret_key' => env('PARTNERSTACK_SECRET_KEY'),
    'base_url' => env('PARTNERSTACK_BASE_URL', 'https://api.partnerstack.com/api/v2'),
];
</code-snippet>
@endverbatim

### Best Practices

- Every method returns the raw decoded JSON response as an array — there are no DTOs to keep the client thin.
- Pass cursor pagination options (`limit`, `starting_after`, `ending_before`, `order_by`) as the array argument of any `list*()` method.
- Money fields (`amount` on transactions and rewards) are integers in cents — never pass a float.
- Always wrap calls in a `try`/`catch` for `\JeffersonGoncalves\PartnerStack\Exceptions\PartnerStackException` — it is thrown on any non-2xx response and carries the API's message plus the HTTP status code.
