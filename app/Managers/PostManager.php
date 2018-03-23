<?php

namespace App\Managers;

use App\Traits\Copy;
use App\Traits\Delete;
use App\Traits\Slug;
use Michelf\Markdown;

class PostManager
{
    use Copy,
        Delete,
        Slug;

    /**
     * Config options
     *
     * @protected
     * @var array
     */
    protected $config = [];

    /**
     * Path to cache folder
     *
     * @protected
     * @var string
     */
    protected $cache_path = '';

    /**
     * Path to public folder
     *
     * @protected
     * @var string
     */
    protected $public_path = '';

    /**
     * Post cache
     *
     * @protected
     * @var array
     */
    protected $post_cache = [];

    /**
     * Create instance
     *
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;

        $this->cache_path = __DIR__ . '/../../cache/';
        @mkdir($this->cache_path);
        @mkdir($this->cache_path . 'feeds/');
        @mkdir($this->cache_path . 'posts/');

        $this->public_path = __DIR__ . '/../../public/data/';

        $this->loadPostCache();
    }

    /**
     * Load post cache into memory
     *
     * @protected
     * @return void
     */
    protected function loadPostCache()
    {
        $temp_cache = [];
        $files = scandir($this->cache_path . 'raw/');
        foreach ($files as $file) {
            if ($file != '' && $file[0] != '.') {
                $data = json_decode(file_get_contents($this->cache_path . 'raw/' . $file), true);
                $temp_cache[ strtotime($data['date']) ] = $data['id'];
            }
        }

        ksort($temp_cache);

        $this->post_cache = array_values($temp_cache);
    }

    /**
     * Reassemble JSON feeds
     *
     * @protected
     * @return void
     */
    protected function assembleJSONFeeds()
    {
        $feed_path = $this->cache_path . 'feeds/';
        $post_path = $this->cache_path . 'posts/';

        // Remove old feed files
        $this->deleteDirectory($feed_path);
        $this->deleteDirectory($post_path);
        @mkdir($feed_path);
        @mkdir($post_path);

        // Calculate pages
        $pages = ceil(count($this->post_cache) / $this->config['page_count']);

        // Assemble base info
        $feed = [
            'version'       => 'https://jsonfeed.org/version/1',
            'title'         => $this->config['title'],
            'description'   => $this->config['description'],
            'home_page_url' => $this->config['base_url'],
            'feed_url'      => $this->config['base_url'] . 'data/feeds/1.json',
            'items'         => [],
            '_atto'         => [
                'total_posts'   => count($this->post_cache),
                'total_pages'   => $pages,
                'current_page'  => 0,
                'per_page'      => $this->config['page_count'],
            ],
        ];

        if (isset($this->config['author'])) {
            $feed['author'] = $this->config['author'];
        }

        // Assemble each page
        for ($page = 0; $page < $pages; ++$page) {
            $feed['_atto']['current_page'] = $page + 1;
            $feed['items'] = [];

            if ($pages > 1 && $page + 2 <= $pages) {
                $feed['next_url'] = $this->config['base_url'] . 'data/feeds/' . ($page + 2) . '.json';
            } else {
                unset($feed['next_url']);
            }

            $items = array_slice(
                array_reverse($this->post_cache),
                $page * $this->config['page_count'],
                $this->config['page_count']
            );

            foreach ($items as $slug) {
                $post = file_get_contents($this->cache_path . 'raw/' . $slug . '.json');
                $post = json_decode($post, true);

                $post_data = [
                    'id'                => $slug,
                    'title'             => $post['title'],
                    'url'               => $this->config['base_url'] . '#/' . $slug,
                    'date_published'    => $post['date'],
                    'content_html'      => $post['content'],
                    '_atto'             => [
                        'date_display'  => date(
                            $this->config['date_display'],
                            strtotime($post['date'])
                        ),
                    ],
                ];

                $feed['items'] []= $post_data;
                file_put_contents($post_path . $slug . '.json', json_encode($post_data));
            }

            file_put_contents($feed_path . ($page + 1) . '.json', json_encode($feed));
        }

        if ($pages == 0) {
            file_put_contents($feed_path . '1.json', json_encode($feed));
        }
    }

    /**
     * Put cache data into public
     *
     * @protected
     * @return void
     */
    protected function publishCacheData()
    {
        $this->deleteDirectory($this->public_path);
        @mkdir($this->public_path);
        $this->copyDirectory($this->cache_path . 'posts', $this->public_path . 'posts');
        $this->copyDirectory($this->cache_path . 'feeds', $this->public_path . 'feeds');
    }

    /**
     * Compile post and add data
     *
     * @param string $file_path
     * @return void
     */
    public function add($file)
    {
        // Load Markdown
        $title = FALSE;
        $lines = [];
        while ($line = fgets($file)) {
            if (!$title) {
                $title = trim(str_replace('#', '', $line));
                continue;
            }

            $lines []= $line;
        }

        // Make slug
        $slug = date($this->config['date_url']) . '-' . $this->createSlug($title);

        // Convert to HTML
        $post_markdown = implode('', $lines);
        $post_html = Markdown::defaultTransform($post_markdown);

        // Save HTML in JSON
        $data = [
            'id'        => $slug,
            'title'     => $title,
            'date'      => date('c'),
            'content'   => $post_html,
        ];
        file_put_contents($this->cache_path . 'raw/' . $slug . '.json', json_encode($data));

        $this->post_cache[ strtotime($data['date']) ] = $data['id'];

        $this->rebuild();
    }

    /**
     * Remove a post by ID
     *
     * @param string $post_id
     * @return void
     */
    public function remove($post_id)
    {
        $index = array_search($post_id, $this->post_cache);
        if ($index === FALSE) {
            exit();
        }

        unset($this->post_cache[$index]);
        @unlink($this->cache_path . 'raw/' . $post_id . '.json');

        $this->rebuild();
    }

    /**
     * Show recent posts
     *
     * @param int $count
     * @return void
     */
    public function list($count)
    {
        $raw_posts = array_slice($this->post_cache, -1 * $count);
        $raw_posts = array_reverse($raw_posts);

        $posts = [];
        foreach ($raw_posts as $time => $slug) {
            $raw_content = file_get_contents($this->cache_path . 'posts/' . $slug . '.json');
            $posts []= json_decode($raw_content, true);
        }

        return $posts;
    }

    /**
     * Rebuild post lists
     *
     * @return void
     */
    public function rebuild()
    {
        $this->assembleJSONFeeds();
        $this->publishCacheData();
    }
}
