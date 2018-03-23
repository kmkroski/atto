<?php

namespace App;

use App\Commands\AddCommand;
use App\Commands\BuildCommand;
use App\Commands\HelpCommand;
use App\Commands\ListCommand;
use App\Commands\PublishCommand;
use App\Commands\RemoveCommand;
use App\Managers\ConfigManager;

class Atto
{
    /**
     * Config variables
     *
     * @var array
     */
    protected $config = [];

    /**
     * Create a new Atto instance
     *
     * @param array $config
     * @return void
     */
    public function __construct($config)
    {
        $this->config = (new ConfigManager($config))->getConfig();

        date_default_timezone_set($this->config['timezone']);
    }

    /**
     * Handle command
     *
     * @param array $arguments
     * @return void
     */
    public function handle($arguments)
    {
        switch (isset($arguments[1]) ? $arguments[1] : 'help') {
            case 'add':
                (new AddCommand($this->config))->handle(isset($arguments[2]) ? $arguments[2] : NULL);
                break;

            case 'build':
                (new BuildCommand($this->config))->handle();
                break;

            case 'help':
                (new HelpCommand($this->config))->handle(isset($arguments[2]) ? $arguments[2] : NULL);
                break;

            case 'list':
                (new ListCommand($this->config))->handle(isset($arguments[2]) ? $arguments[2] : NULL);
                break;

            case 'publish':
                (new PublishCommand($this->config))->handle(isset($arguments[2]) ? $arguments[2] : NULL);
                break;

            case 'remove':
                (new RemoveCommand($this->config))->handle(isset($arguments[2]) ? $arguments[2] : NULL);
                break;

            default:
                echo chr(27) . '[31mUnknown command!' . chr(27) .'[0m' . PHP_EOL;
                break;
        }
    }
}
