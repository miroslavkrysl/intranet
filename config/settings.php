<?php
return [
    // core
    'app' => [
        'name' => env('app.name'),
        'url' => env('app.url'),
        'locale' => 'cz',
        'fallback-locale' => 'cz',
        'webmaster' => 'mirek.krysl@seznam.cz'
    ],
    'database' => [
        'type' => env('database.type'),
        'host' => env('database.host'),
        'port' => env('database.port'),
        'dbname' => env('database.name'),
        'username' => env('database.username'),
        'password' => env('database.password')
    ],
    'cookie' => [
        'expire' => 120 * 86400,
        'path' => '/',
        'domain' => env('app.url')
    ],
    'validator' => [
        'language-prefix' => 'validation'
    ],

    // app
    'csrf' => [
        'expire' => 15 // minutes
    ]
];
