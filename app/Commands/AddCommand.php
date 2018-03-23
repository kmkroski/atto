<?php

namespace App\Commands;

use App\Managers\PostManager;

class AddCommand extends AbstractCommand
{
    /**
     * Open file and add data
     *
     * @param string $file_path = NULL
     * @return void
     */
    public function handle($file_path = NULL)
    {
        if (is_null($file_path)) {
            $this->error('A file is required.');
            exit();
        }

        try {
            $file = fopen($file_path, 'r');
        } catch(\Exception $e) {
            $this->error('Unable to open file.');
            exit();
        }

        $posts = new PostManager($this->config);
        $posts->add($file);

        $this->info('Added post!');
    }
}
