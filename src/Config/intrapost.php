<?php

return [
    'api_key' => env('INTRAPOST_API_KEY', ''),
    'account_number' => env('INTRAPOST_ACCOUNT_NUMBER', ''),
    'base_url' => env('INTRAPOST_BASE_URL', 'https://api.intrapost.nl'),
    'timeout' => env('INTRAPOST_TIMEOUT', 30),
];
