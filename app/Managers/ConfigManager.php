<?php

namespace App\Managers;

class ConfigManager
{
    /**
     * Assembled config options
     *
     * @protected
     * @var array
     */
    protected $assembled_config = [];

    /**
     * Default config options
     *
     * @protected
     * @var array
     */
    protected $default_config = [
        // Required Options
        'local_url'         => "http://localhost/",
        'remote_url'        => "http://remotehost/",
        'title'             => "Atto Blog",
        'description'       => "A micro blog powered by Atto.",

        // Display Options
        'favicon'           => 'images/favicon/favicon.ico',
        'page_count'        => 10,

        // Theme Options
        'theme'             => 'default',
        'theme_variables'   => [],

        // Time Options
        'timezone'          => 'America/Chicago',
        'date_file'         => 'Ymd-His',
        'date_url'          => 'Y-m-d',
        'date_display'      => 'Y-m-d g:i A',

        // FTP Options
        'ftp'               => [
            'ssl'           => false,
            'ssl_port'      => 22,
            'host'          => 'FTP_HOST',
            'username'      => 'FTP_USERNAME',
            'directory'     => '/',
        ],
    ];

    /**
     * Create instance
     *
     * @param array $user_config
     * @return array
     */
    public function __construct(array $user_config)
    {
        $this->assembled_config = array_merge($this->default_config, $user_config);
        $this->assembled_config['base_url'] = $this->assembled_config['local_url'];
    }

    /**
     * Get config array
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->assembled_config;
    }
}
