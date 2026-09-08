<?php

namespace JeffersonGoncalves\PartnerStack;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\PartnerStack\Exceptions\PartnerStackException;

/**
 * Thin client for the PartnerStack v2 REST API
 * (https://api.partnerstack.com/api/v2). Every method returns the raw decoded
 * JSON response as an array and authenticates the request with the public and
 * secret keys sent as HTTP Basic credentials.
 *
 * List endpoints accept the API's cursor pagination options as `$query`:
 * `limit`, `starting_after`, `ending_before` and `order_by`.
 */
class PartnerStack
{
    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function listPartnerships(array $query = []): array
    {
        return $this->get('/partnerships', $query);
    }

    /**
     * @return array<string, mixed>
     */
    public function getPartnership(string $key): array
    {
        return $this->get("/partnerships/{$key}");
    }

    /**
     * @param  array{email: string, group_key: string, name?: string, first_name?: string, last_name?: string}  $data
     * @return array<string, mixed>
     */
    public function createPartnership(array $data): array
    {
        return $this->post('/partnerships', $data);
    }

    /**
     * @param  array{name?: string, group_key?: string}  $data
     * @return array<string, mixed>
     */
    public function updatePartnership(string $key, array $data): array
    {
        return $this->patch("/partnerships/{$key}", $data);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function listCustomers(array $query = []): array
    {
        return $this->get('/customers', $query);
    }

    /**
     * @return array<string, mixed>
     */
    public function getCustomer(string $key): array
    {
        return $this->get("/customers/{$key}");
    }

    /**
     * @param  array{email: string, partner_key: string, name?: string, customer_key?: string}  $data
     * @return array<string, mixed>
     */
    public function createCustomer(array $data): array
    {
        return $this->post('/customers', $data);
    }

    /**
     * @param  array{name?: string, email?: string, partner_key?: string}  $data
     * @return array<string, mixed>
     */
    public function updateCustomer(string $key, array $data): array
    {
        return $this->patch("/customers/{$key}", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteCustomer(string $key): array
    {
        return $this->delete("/customers/{$key}");
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function listTransactions(array $query = []): array
    {
        return $this->get('/transactions', $query);
    }

    /**
     * @return array<string, mixed>
     */
    public function getTransaction(string $key): array
    {
        return $this->get("/transactions/{$key}");
    }

    /**
     * The `amount` is in cents.
     *
     * @param  array{customer_key: string, amount: int, currency?: string, category?: string, product_key?: string}  $data
     * @return array<string, mixed>
     */
    public function createTransaction(array $data): array
    {
        return $this->post('/transactions', $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteTransaction(string $key): array
    {
        return $this->delete("/transactions/{$key}");
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function listDeals(array $query = []): array
    {
        return $this->get('/deals', $query);
    }

    /**
     * @return array<string, mixed>
     */
    public function getDeal(string $key): array
    {
        return $this->get("/deals/{$key}");
    }

    /**
     * @param  array{partner_key: string, name: string, amount?: int, currency?: string, stage?: string}  $data
     * @return array<string, mixed>
     */
    public function createDeal(array $data): array
    {
        return $this->post('/deals', $data);
    }

    /**
     * @param  array{name?: string, amount?: int, stage?: string, status?: string}  $data
     * @return array<string, mixed>
     */
    public function updateDeal(string $key, array $data): array
    {
        return $this->patch("/deals/{$key}", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function archiveDeal(string $key): array
    {
        return $this->delete("/deals/{$key}");
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function listActions(array $query = []): array
    {
        return $this->get('/actions', $query);
    }

    /**
     * @param  array{customer_key: string, key: string, value?: int}  $data
     * @return array<string, mixed>
     */
    public function createAction(array $data): array
    {
        return $this->post('/actions', $data);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function listRewards(array $query = []): array
    {
        return $this->get('/rewards', $query);
    }

    /**
     * The `amount` is in cents.
     *
     * @param  array{partner_key: string, amount: int, description?: string, currency?: string}  $data
     * @return array<string, mixed>
     */
    public function createReward(array $data): array
    {
        return $this->post('/rewards', $data);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function listLeads(array $query = []): array
    {
        return $this->get('/leads', $query);
    }

    /**
     * @return array<string, mixed>
     */
    public function getLead(string $key): array
    {
        return $this->get("/leads/{$key}");
    }

    /**
     * @param  array{partner_key: string, email: string, name?: string, company?: string}  $data
     * @return array<string, mixed>
     */
    public function createLead(array $data): array
    {
        return $this->post('/leads', $data);
    }

    /**
     * @param  array{email?: string, name?: string, status?: string}  $data
     * @return array<string, mixed>
     */
    public function updateLead(string $key, array $data): array
    {
        return $this->patch("/leads/{$key}", $data);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function listGroups(array $query = []): array
    {
        return $this->get('/groups', $query);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function listWebhooks(array $query = []): array
    {
        return $this->get('/webhooks', $query);
    }

    /**
     * @return array<string, mixed>
     */
    public function getWebhook(string $key): array
    {
        return $this->get("/webhooks/{$key}");
    }

    /**
     * @param  array{target: string, events?: list<string>}  $data
     * @return array<string, mixed>
     */
    public function createWebhook(array $data): array
    {
        return $this->post('/webhooks', $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteWebhook(string $key): array
    {
        return $this->delete("/webhooks/{$key}");
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    private function get(string $uri, array $query = []): array
    {
        return $this->handle($this->http()->get($uri, $query));
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    private function post(string $uri, array $body = []): array
    {
        return $this->handle($this->http()->post($uri, $body));
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    private function patch(string $uri, array $body = []): array
    {
        return $this->handle($this->http()->patch($uri, $body));
    }

    /**
     * @return array<string, mixed>
     */
    private function delete(string $uri): array
    {
        return $this->handle($this->http()->delete($uri));
    }

    private function http(): PendingRequest
    {
        return Http::withBasicAuth(
            (string) config('partnerstack.public_key'),
            (string) config('partnerstack.secret_key'),
        )
            ->baseUrl((string) config('partnerstack.base_url', 'https://api.partnerstack.com/api/v2'))
            ->acceptJson();
    }

    /**
     * @return array<string, mixed>
     *
     * @throws PartnerStackException
     */
    private function handle(Response $response): array
    {
        if ($response->failed()) {
            throw new PartnerStackException($this->errorMessage($response), $response->status());
        }

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    private function errorMessage(Response $response): string
    {
        $data = $response->json();

        if (is_array($data)) {
            foreach (['message', 'error'] as $field) {
                if (is_string($data[$field] ?? null)) {
                    return $data[$field];
                }
            }
        }

        return $response->body() !== ''
            ? $response->body()
            : "PartnerStack API request failed with status {$response->status()}.";
    }
}
