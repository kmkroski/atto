<?php

namespace App\Commands;

use App\Managers\PostManager;

class RemoveCommand extends AbstractCommand
{
    /**
     * Open file and add data
     *
     * @param string $file_path = NULL
     * @return void
     */
    public function handle($post_id = NULL)
    {
        if (is_null($post_id)) {
            $this->error('A post ID is required.');
            exit();
        }

        $posts = new PostManager($this->config);
        $posts->remove($post_id);

        $this->info('Removed post!');
    }
}
