<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Obaida Framework',
    'env' => $_ENV['APP_ENV'] ?? 'development',
    'debug' => $_ENV['APP_DEBUG'] ?? true,
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    // 'timezone' => 'UTC',
    // 'locale' => 'en',
    'timezone' => 'Africa/Cairo',
    'locale' => 'ar',
    
    // Security and Encryption Settings
    'key' => $_ENV['APP_KEY'] ?? null,
    'cipher' => 'AES-256-CBC',
    'hash' => [
        'algo' => PASSWORD_BCRYPT,
        'cost' => 10
    ],
    
    // Security Headers
    'headers' => [
        'x-frame-options' => 'SAMEORIGIN',
        'x-xss-protection' => '1; mode=block',
        'x-content-type-options' => 'nosniff'
    ],
    
    // Session Configuration
    'session' => [
        'lifetime' => $_ENV['SESSION_LIFETIME'] ?? 120,
        'secure' => false,
        'http_only' => true,
        'same_site' => 'lax'
    ]
];