<?php

namespace App\Commands;

use App\Traits\Copy;
use App\Traits\Delete;
use Leafo\ScssPhp\Compiler as ScssCompiler;
use App\Managers\PostManager;

class BuildCommand extends AbstractCommand
{
    use Copy,
        Delete;

    /**
     * Path to public folder
     *
     * @protected
     * @var string
     */
    protected $public_path = '';

    /**
     * Path to theme folder
     *
     * @protected
     * @var string
     */
    protected $theme_path = '';

    /**
     * Create instance
     *
     * @param array $config
     * @return void
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->public_path = __DIR__ . '/../../public/';
        $this->theme_path = __DIR__ . '/../../themes/' . $this->config['theme'] . '/';
    }

    /**
     * Handle images
     *
     * @return void
     */
    protected function handleImages()
    {
        $this->deleteDirectory($this->public_path . 'images/');

        if (!file_exists($this->theme_path . 'images/')) {
            return;
        }

        $this->copyDirectory($this->theme_path . 'images/', $this->public_path . 'images/');
    }

    /**
     * Handle fonts
     *
     * @return void
     */
    protected function handleFonts()
    {
        $this->deleteDirectory($this->public_path . 'fonts/');

        if (!file_exists($this->theme_path . 'fonts/')) {
            return;
        }

        $this->copyDirectory($this->theme_path . 'fonts/', $this->public_path . 'fonts/');
    }

    /**
     * Handle scripts
     *
     * @return void
     */
    protected function handleScripts()
    {
        $this->deleteDirectory($this->public_path . 'js/');
        $this->copyDirectory($this->theme_path . 'js/', $this->public_path . 'js/');
    }

    /**
     * Handle styles
     *
     * @return void
     */
    protected function handleStyles()
    {
        $this->deleteDirectory($this->public_path . 'css/');
        mkdir($this->public_path . 'css');

        if (file_exists($this->theme_path . 'scss/')) {
            $scss_compiler = new ScssCompiler();
            $scss_compiler->setImportPaths($this->theme_path . 'scss/');
            $scss_compiler->setVariables($this->config['theme_variables']);
            $scss_compiler->setFormatter('Leafo\ScssPhp\Formatter\Crunched');

            $style = $scss_compiler->compile(file_get_contents($this->theme_path . 'scss/app.scss'));
            file_put_contents($this->public_path . 'css/app.css', $style);
        } elseif (file_exists($this->theme_path . 'css/')) {
            $this->copyDirectory($this->theme_path . 'css/', $this->public_path . 'css/');
        }
    }

    /**
     * Handle index.php
     *
     * @protected
     * @return void
     */
    protected function handleIndex()
    {
        ob_start();
        include $this->theme_path . 'index.php';
        $index_html = ob_get_contents();
        ob_end_clean();

        file_put_contents($this->public_path . 'index.html', $index_html);
    }

    /**
     * Assemble public assets
     *
     * @param string $command
     * @return void
     */
    public function handle()
    {
        $this->info('Copying images...');
        $this->handleImages();

        $this->info('Copying fonts...');
        $this->handleFonts();

        $this->info('Copying scripts...');
        $this->handleScripts();

        $this->info('Building styles...');
        $this->handleStyles();

        $this->info('Building index...');
        $this->handleIndex();

        $posts = new PostManager($this->config);
        $posts->rebuild();
    }
}
