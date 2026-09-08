<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PartnerStack API Keys
    |--------------------------------------------------------------------------
    |
    | Your PartnerStack public and secret keys, sent together as HTTP Basic
    | credentials on every request. Find them in your PartnerStack dashboard
    | under Settings > Integrations > API keys.
    |
    */
    'public_key' => env('PARTNERSTACK_PUBLIC_KEY'),

    'secret_key' => env('PARTNERSTACK_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The PartnerStack v2 REST API base URL. Override only if PartnerStack
    | gives you a dedicated endpoint.
    |
    */
    'base_url' => env('PARTNERSTACK_BASE_URL', 'https://api.partnerstack.com/api/v2'),
];
