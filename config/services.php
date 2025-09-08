<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'ga4' => [
        'measurementId' => env('GA_MEASUREMENT_ID'),
    ],
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'api_token' => env('API_LOOKUP_TOKEN', '1|tl48jBRLgYHbBoQWmYX0ZRPkK8WAgBS4oGn46Xii6bcbf35b'),
    'auth_2earn' => [
        'client_id' => env('AUTH_2EARN_CLIENT_ID'),
        'redirect' => env('AUTH_2EARN_REDIRECT_URL'),
        'authorise' => env('AUTH_2EARN_AUTHORISE_URL'),
        'secret' => env('AUTH_2EARN_CLIENT_SECRET'),
    ],

];
