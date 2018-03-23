<?php

namespace App\Commands;

use App\Managers\PostManager;
use FtpClient\FtpClient;

class PublishCommand extends AbstractCommand
{
    /**
     * Push public to server
     *
     * @param string $password
     * @return void
     */
    public function handle($password)
    {
        if (is_null($password)) {
            $this->error('An FTP password is required!');
            exit();
        }

        $ftp = new FtpClient();

        $this->info('Connecting to FTP...');
        if ($this->config['ftp']['ssl']) {
            $ftp->connect($this->config['ftp']['host'], true, $this->config['ftp']['ssl_port']);
        } else {
            $ftp->connect($this->config['ftp']['host']);
        }
        $ftp->login($this->config['ftp']['username'], $password);
        $this->success('Connected to FTP!');

        $this->info('Building remote files...');
        $this->config['base_url'] = $this->config['remote_url'];
        $build = new BuildCommand($this->config);
        $build->handle();
        $posts = new PostManager($this->config);
        $posts->rebuild();
        $this->success('Built remote files!');

        $this->info('Uploading all files...');
        $ftp->putAll(__DIR__ . '/../../public/', $this->config['ftp']['directory']);
        $this->success('Copied all files!');

        $this->info('Replacing local files...');
        $this->config['base_url'] = $this->config['local_url'];
        $build = new BuildCommand($this->config);
        $build->handle();
        $posts = new PostManager($this->config);
        $posts->rebuild();
        $this->success('Replaced local files!');
    }
}
