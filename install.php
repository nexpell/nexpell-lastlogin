<?php

if (!function_exists('safe_query')) {
    die('Access denied');
}

global $plugin;

PluginInstallerHelper::install([

    'modulname'  => 'lastlogin',
    'name'       => 'LastLogin',
    'version'    => (string)($plugin['version'] ?? '1.0.0'),
    'author'     => 'T-Seven',
    'website'    => 'https://www.nexpell.de',
    'path'       => 'includes/plugins/lastlogin/',

    'admin_file' => 'admin_lastlogin',
    'index_link' => '',
    'sidebar'    => 'deactivated',

    'languages' => [
        'plugin_info_lastlogin' => [
            'de' => 'Mit diesem Plugin ist es möglich, die Aktivität der User und Mitglieder zu überprüfen.',
            'en' => 'With this plugin it is possible to check the activity of the users and members.',
            'it' => 'Con questo plugin è possibile controllare l’attività degli utenti e dei membri.'
        ]
    ],

    'permissions' => [
        'lastlogin'
    ],

    'admin_navigation' => [
        [
            'url'   => 'admincenter.php?site=admin_lastlogin',
            'catID' => 3,
            'sort'  => 2,
            'labels' => [
                'de' => 'Letzte Anmeldung',
                'en' => 'Last Login',
                'it' => 'Ultimi Login'
            ]
        ]
    ]

]);
