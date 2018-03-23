<?php

namespace App\Commands;

abstract class AbstractCommand
{
    /**
     * Config options
     *
     * @protected
     * @var array
     */
    protected $config = [];

    /**
     * Create instance
     *
     * @param array $config
     * @return void
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Display error message
     *
     * @protected
     * @param string $message
     * @return void
     */
    protected function error($message)
    {
        echo chr(27) . '[31m' . $message . chr(27) .'[0m' . PHP_EOL;
    }

    /**
     * Display success message
     *
     * @protected
     * @param string $message
     * @return void
     */
    protected function success($message)
    {
        echo chr(27) . '[32m' . $message . chr(27) .'[0m' . PHP_EOL;
    }

    /**
     * Display info message
     *
     * @protected
     * @param string $message
     * @return void
     */
    protected function info($message)
    {
        echo $message . PHP_EOL;
    }
}
