<?php
return [
    'menu' => [
        'documents' => 'dokumenty',
        'requests' => 'žádanky o auta',
        'request' => 'žádanka',
        'home' => 'home',
        'users' => 'uživatelé',
        'settings' => 'nastavení',
        'cars' => 'Služební auta'
    ],
    'yes' => 'ano',
    'no' => 'ne',
    'save' => 'uložit',
    'close' => 'zavřít',
    'send' => 'odeslat',
    'delete' => 'odstranit',
    'download' => 'stáhnout',
    'invalid_link' => 'neplatný odkaz',
    'user' => [
        'no_users' => 'žádní uživatelé',
        'update' => [
            'success' => 'Údaje byly úspěšně aktualizovány',
            'admin_role_denied' => 'Admin nemůže sám sobě změnit roli.'
        ],
        'create' => [
            'success' => 'Uživatel byl úspěšně vytvořen, odkaz pro vytvoření hesla byl zaslán na danou adresu',
            'action' => 'Vytvořit nového uživatele',
            'mail' => [
                'message' => 'Na webovém serveru <a href="http://' . config('app.url') . '">Intranetu OŠVS</a> Vám byl vytvořen účet.<br /><br />'.
                    'Uživatelské jméno: :username <br /><br />'.
                    'Pro vytvoření hesla klikněte na odkaz níže, budete přesměrováni na formulář, kde lze Vaše heslo vytvořit.<br />'.
                    '<a href=":url">Vytvoření hesla</a><br /><br />',
                'subject' => 'Intranet OŠVS - vytvoření účtu'
            ]
        ],
        'change_password' => [
            'success' => 'Heslo bylo úspěšně změněno',
            'action' => 'Změna hesla',
            'invalid_token' => 'Neplatný klíč, zkuste žádost o změnu hesla poslat znovu',
            'invalid_password' => 'Bylo zadáno špatné heslo',
            'no_auth' => 'Nebylo zadáno heslo, ani autorizační klíč',
            'mail' => [
                'sent' => 'E-mail s odkazem pro změnu hesla byl zaslán, pokud je adresa platná.',
                'message' => 'Na webovém serveru <a href="http://' . config('app.url') . '">Intranetu OŠVS</a> byla podána žádost o změnu hesla.<br /><br />'.
                    'Klikněte na odkaz níže, budete přesměrováni na formulář, kde lze zadat nové heslo.<br />'.
                    '<a href=":url">Změna hesla</a><br /><br />'.
                    'Platnost odkazu je omezená.',
                'subject' => 'Intranet OŠVS - změna hesla'
            ]
        ],
        'delete' => [
            'action' => 'Odstranit uživatele',
            'confirm' => 'Přejete si opravdu odstranit uživatele',
            'success' => 'Uživatel :username byl odstraněn',
            'delete_self_denied' => 'Nemůžete odstranit sami sebe'
        ]
    ],
    'car' => [
        'no_requests' => 'žádné žádanky',
        'update' => [
            'success' => 'Údaje byly úspěšně aktualizovány'
        ],
        'create' => [
            'success' => 'Auto :name bylo úspěšně přidáno',
            'action' => 'Přidat nové auto'
        ],
        'delete' => [
            'action' => 'Odstranit auto',
            'confirm' => 'Přejete si opravdu odstranit auto',
            'success' => 'Auto :name bylo odstraněno'
        ]
    ],
    'document' => [
        'no_documents' => 'žádné dokumenty',
        'file_already_exists' => 'Dokument :name již existuje',
        'file_not_given' => 'Nebyl předán žádný soubor',
        'update' => [
            'success' => 'Dokument byl úspěšně aktualizován'
        ],
        'create' => [
            'success' => 'Dokument :name byl úspěšně přidán',
            'action' => 'Přidat nový dokument'
        ],
        'delete' => [
            'action' => 'Odstranit dokument',
            'confirm' => 'Přejete si opravdu odstranit dokument',
            'success' => 'Document :name byl odstraněn'
        ]
    ],
    'request' => [
        'reserved_to_before_from' => 'Konec rezervace nemůže být před začátkem rezervace',
        'reserved_from_before_now' => 'Začátek rezervace nemůže být v minulosti',
        'update' => [
            'success' => 'Žádanka byla úspěšně aktualizována'
        ],
        'create' => [
            'success' => 'Žádanka byla úspěšně podána',
            'action' => 'Podat žádanku'
        ],
        'delete' => [
            'action' => 'Odstranit žádanku',
            'confirm' => 'Přejete si opravdu odstranit žádanku?',
            'success' => 'Žádanka byla odstraněna'
        ],
        'confirm' => [
            'action' => 'Potvrdit žádanku',
            'confirm' => 'Přejete si opravdu potvrdit žádanku?',
            'success' => 'Žádanka byla potvrzena'
        ]
    ],
    'user_can_drive' => [
        'cant_drive' => "Uživatel :username nemůže řídit auto :car_name",
        'add' => [
            'action' => 'Přidat uživatele',
            'success' => 'Uživatel :username smí nyní řídit auto :car_name'
        ],
        'delete' => [
            'success' => 'Uživatel :username nyní již nesmí řídit auto :car_name'
        ]
    ],
    'users_can_drive' => 'uživatelé, kteří smějí řídit toto auto:',
    'auth' => [
        'login' => 'přihlásit se',
        'logout' => 'odhlásit se',
        'remember' => 'Pamatovat si mě',
        'password' => [
            'forgotten' => 'Zapomněli jste heslo?',
            'forgotten_title' => 'Zapomenuté heslo',
            'forgotten_text' => 'Zapomněli jste heslo? Zadejte e-mailovou adresu, která je přidružená k vašemu účtu. Pokud adresa existuje v databázi intranetu, bude Vám zaslán odkaz pro změnu hesla.',
            'forgotten_spam_text' => 'Z důvodu zamezení spamu lze e-mail odeslat maximálně jednou za 3 hodiny.',
            'wrong' => 'Bylo zadáno špatné heslo',
            'empty' => 'Nebylo zadáno heslo'
        ],
        'not_logged' => 'Nejste přihlášen, zkuste stránku znovu načíst'
    ]
];
