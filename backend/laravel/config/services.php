<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'ses' => [
        'key' => env('AWS_SES_ACCESS_KEY'),
        'secret' => env('AWS_SES_SECRET_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'ap-south-1'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'setu' => [
        'client_id' => env('SETU_CLIENT_ID'),
        'client_secret' => env('SETU_CLIENT_SECRET'),
        'base_url' => env('SETU_BASE_URL', 'https://dg.setu.co'),
    ],

    'digio' => [
        'api_key' => env('DIGIO_API_KEY'),
        'base_url' => env('DIGIO_BASE_URL', 'https://ext.digio.in:444'),
    ],

    'msg91' => [
        'auth_key' => env('MSG91_AUTH_KEY'),
        'sender_id' => env('MSG91_SENDER_ID', 'MDIGSH'),
        'template_id' => env('MSG91_TEMPLATE_ID'),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
    ],

    'ollama' => [
        'url' => env('OLLAMA_URL', 'http://localhost:11434'),
        'model' => env('OLLAMA_MODEL', 'mistral:7b'),
    ],

];
