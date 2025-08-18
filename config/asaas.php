<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Asaas API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for the Asaas API.
    |
    */

    'api_url' => env('ASAAS_API_URL', 'https://www.asaas.com/api/v3'),

    'api_key' => env('ASAAS_API_KEY'),

    'webhook_secret' => env('ASAAS_WEBHOOK_SECRET'),
];
