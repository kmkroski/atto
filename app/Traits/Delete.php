<?php

namespace App\Traits;

trait Delete
{
    /**
     * Delete directory
     *
     * @param string $dir
     * @return void
     */
    public function deleteDirectory($dir)
    {
        $files = @scandir($dir);
        if (!$files) {
            return;
        }

        foreach (array_diff($files, ['.', '..']) as $file) {
            is_dir("$dir/$file") ? $this->deleteDirectory("$dir/$file") : unlink("$dir/$file");
        }

        return @rmdir($dir);
    }
}
