<?php

namespace App\Commands;

class HelpCommand extends AbstractCommand
{
    /**
     * Show help
     *
     * @param string $command = NULL
     * @return void
     */
    public function handle($command = NULL)
    {
        switch ($command) {
            case 'add':
                $this->success('add:');
                $this->info('   Add a new post from an existing file.');
                $this->info('   `php atto add FILE_PATH`');
                break;

            case 'build':
                $this->success('build:');
                $this->info('   Rebuild the blog public assets.');
                $this->info('   `php atto build`');
                break;

            case 'help':
                $this->success('help:');
                $this->info('   Displays a list of commands or information on a single command.');
                $this->info('   `php atto help COMMAND?`');
                break;

            case 'list':
                $this->success('list:');
                $this->info('   Displays a list of recent posts.');
                $this->info('   `php atto list COUNT?`');
                break;

            case 'publish':
                $this->success('publish:');
                $this->info('   Publish all new posts online.');
                $this->info('   `php atto publish PASSWORD`');
                break;

            case 'remove':
                $this->success('remove:');
                $this->info('   Remove an existing post');
                $this->info('   `php atto remove ID`');
                break;

            default:
                $this->success('Atto Control Tool');
                $this->info('   add      : Add a new post');
                $this->info('   build    : Rebuild the index page');
                $this->info('   help     : Displays a list of commands');
                $this->info('   list     : Lists recent posts');
                $this->info('   publish  : Publish posts to server');
                $this->info('   remove   : Remove a post');
                break;
        }
    }
}
