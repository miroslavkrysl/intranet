<?php
return [
    "type" => env("database.type"),
    "host" => env("database.host"),
    "port" => env("database.port"),
    "dbname" => env("database.name"),
    "username" => env("database.username"),
    "password" => env("database.password"),
    "tables" => [
        "user" => "user",
        "car" => "car",
        "request" => "request",
        "document" => "document"
    ]
];