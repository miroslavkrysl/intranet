<?php
return [
    // core
    'app' => [
        'name' => env('app.name'),
        'url' => env('app.url'),
        'locale' => 'cz',
        'fallback_locale' => 'cz',
        'webmaster' => 'mirek.krysl@seznam.cz'
    ],
    'database' => [
        'type' => env('database.type'),
        'host' => env('database.host'),
        'port' => env('database.port'),
        'dbname' => env('database.name'),
        'username' => env('database.username'),
        'password' => env('database.password'),
        'tables' => [
            'user' => 'user',
            'login' => 'login',
            'car' => 'car',
            'request' => 'request',
            'document' => 'document',
            'role' => 'role',
            'role_permission' => 'role_permission',
            'permission' => 'permission',
            'user_can_drive' => 'user_can_drive'
        ]
    ],
    'cookie' => [
        'expire' => 120 * 86400,
        'path' => '/',
        'domain' => env('app.url')
    ],
    'validator' => [
        'lang_prefix_messages' => 'validation',
        'lang_prefix_fields' => 'fields'
    ],

    // app
    'csrf' => [
        'expire' => 15 // minutes
    ],
    'auth' => [
        'login_expire_days' => 64
    ]
];
