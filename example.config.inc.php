<?php

/*
|--------------------------------------------------------------------------
| Atto Configuration Settings
|--------------------------------------------------------------------------
*/

return [
    // Required Options
    'local_url'         => "http://localhost/",
    'remote_url'        => "https://remotehost/",
    'title'             => "Atto Blog",
    'description'       => "A micro blog powered by Atto.",

    // Author
    'author'            => [
        'name'          => 'An Atto User',
        'url'           => 'https://attouser/'
    ],

    // Display Options
    'favicon_path'      => 'images/favicon/',
    'page_count'        => 10,

    // Theme Options
    'theme'             => 'default',
    'theme_variables'   => [
        'backgroundColor'   => '#ecf0f1',
        'accentColor'       => '#1abc9c',
    ],

    // Time Options
    'timezone'          => 'America/Chicago',
    'date_file'         => 'Ymd-His',
    'date_url'          => 'Y-m-d',
    'date_display'      => 'Y-m-d g:i A',

    // FTP Options
    'ftp'               => [
        'ssl'           => false,
        'ssl_port'      => 22,
        'host'          => 'ftp.remotehost',
        'username'      => 'FTP_USERNAME',
        'directory'     => 'public_directory',
    ],
];
