<?php
return [
    'menu' => [
        'documents' => 'dokumenty',
        'requests' => 'žádanky o auta',
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
    'invalid_link' => 'neplatný odkaz',
    'user' => [
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
                'message' => 'Na webovém serveru <a href="' . config('app.url') . '">Intranetu OŠVS</a> byla podána žádost o změnu hesla.<br /><br />'.
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
        ]
    ]
];
