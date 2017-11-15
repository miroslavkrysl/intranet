<?php
return [
    'length' => [
        'min' => [
            '1' => ':Field musí mít alespoň :min znak.',
            '2-4' => ':Field musí mít alespoň :min znaky.',
            '5-*' => ':Field musí mít alespoň :min znaků.'
        ],
        'max' => [
            '1' => ':Field může mít maximálně :max znak.',
            '2-4' => ':Field může mít maximálně :max znaky.',
            '5-*' => ':Field může mít maximálně :max znaků.'
        ]
    ],
    'required' => 'Nebyl zadán povinný údaj :field.',
    'between' => ':Field musí být mezi :from a :to.',
    'date' => ':Field musí být platné datum.',
    'date.after' => 'Datum :field musí být po datu :after.',
    'date.after-date' => 'Datum :field musí být po datu :after.',
    'email' => ':Field musí být platná emailová adresa.'
];