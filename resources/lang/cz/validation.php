<?php
return [
    'min_length' => [
        '1' => ':Field musí mít alespoň :min znak.',
        '2-4' => ':Field musí mít alespoň :min znaky.',
        '5-*' => ':Field musí mít alespoň :min znaků.'
    ],
    'max_length' => [
        '1' => ':Field může mít maximálně :max znak.',
        '2-4' => ':Field může mít maximálně :max znaky.',
        '5-*' => ':Field může mít maximálně :max znaků.'
    ],
    'min' => ':Field musí být větší nebo rovno :min.',
    'max' => ':Field musí být menší nebo rovno :max.',
    'required' => 'Nebyl zadán povinný údaj :field.',
    'date' => ':Field musí být platné datum.',
    'after' => 'Datum :field musí být po datu :after.',
    'email' => ':Field musí být platná emailová adresa.',
    'regex' => ':Field není ve správném tvaru.',
    'exists' => ':Field :value neexistuje'
];