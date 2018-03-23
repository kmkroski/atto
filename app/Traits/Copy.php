<?php

namespace App\Traits;

trait Copy
{
    /**
     * Recursive copy directory
     *
     * @param string $src
     * @param string $dst
     * @return void
     */
    public function copyDirectory($src, $dst) {
        $dir = opendir($src);

        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->copyDirectory($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }

        closedir($dir);
    }
}
