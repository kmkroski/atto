<?php

namespace App\Commands;

use App\Managers\PostManager;

class ListCommand extends AbstractCommand
{
    /**
     * List recent posts
     *
     * @param string $count = NULL
     * @return void
     */
    public function handle($count = NULL)
    {
        if (is_null($count)) {
            $count = $this->config['page_count'];
        }

        $posts = new PostManager($this->config);
        $list = $posts->list($count);

        $this->success('Showing ' . count($list) . ' most recent post' . (count($list) == 1 ? '' : 's') . '...');

        foreach ($list as $post) {
            $this->info('');
            $this->info('ID:    ' . $post['id']);
            $this->info('TITLE: ' . $post['title']);
            $this->info('DATE:  ' . $post['date_published']);
        }

        $this->info('');
    }
}
