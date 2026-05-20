<?php

return [
    // Title of the role that identifies a "Totem" user.
    // Read from .env via config to remain available when config is cached.
    'role_title' => env('ROL_TOTEM', 'Totem'),

    // Pusher/custom realtime settings exposed to Totem devices.
    // Keep them in config so they remain available when `config:cache` is enabled.
    'pusher' => [
        // Historically the project used the misspelled var `PUSHER_CHANEL`.
        // Support both to avoid unexpected nulls.
        'channel' => env('PUSHER_CHANEL') ?: env('PUSHER_CHANNEL'),

        'event_payment' => env('PUSHER_EVENT_PAYMENT') ?: env('PUSHER_EVENT_PAGO'),
        'event_card' => env('PUSHER_EVENT_CARD'),
        'event_card_tesa' => env('PUSHER_EVENT_CARD_TESA') ?: env('PUSHER_EVENT_CARD_TESA2'),
    ],

    'payment' => [
        'uri' => env('PAYMENT_URI'),
        'response_uri' => env('PAYMENT_RESPONSE_URI') ?: env('PAYMENT_RETURN_URI'),
    ],

    'endpoints' => [
        'write_card_uri' => rtrim((string) env('APP_URL'), '/') . (string) env('WRITE_CARD_URI'),
        'control_error_uri' => rtrim((string) env('APP_URL'), '/') . (string) env('CONTROL_ERROR_URI'),
    ],
];
