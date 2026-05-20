<?php

return [
    'misterplan' => [
        // Base URL without trailing slash; defaults to test endpoint
        'base_url' => env('MISTERPLAN_BASE_URL', 'https://test.mrplan.io/scr/modulos/TApiAlojamientos'),
        // API key provided by MisterPlan
        'api_key' => env('MISTERPLAN_API_KEY', ''),
        // Channel ID required in request bodies
        'channel_id' => env('MISTERPLAN_CHANNEL_ID', '3'),
        // HTTP client settings
        'timeout' => env('MISTERPLAN_TIMEOUT', 15),
        'retries' => env('MISTERPLAN_RETRIES', 2),
        'retry_delay_ms' => env('MISTERPLAN_RETRY_DELAY_MS', 300),
        // Feature flag: if false, provider will fall back to Local behavior
        'real_api_enabled' => env('MISTERPLAN_REAL_API', false),
    ],
];
