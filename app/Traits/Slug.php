<?php

namespace App\Traits;

trait Slug
{
    /**
     * Convert string to URL-safe slug
     *
     * @protected
     * @param string $str
     * @param string $delimiter = '-'
     * @return string
     */
    protected function createSlug($str, $delimiter = '-')
    {
        return strtolower(
            trim(
                preg_replace(
                    '/[\s-]+/',
                    $delimiter,
                    preg_replace(
                        '/[^A-Za-z0-9-]+/',
                        $delimiter,
                        preg_replace(
                            '/[&]/',
                            'and',
                            preg_replace(
                                '/[\']/',
                                '',
                                iconv('UTF-8', 'ASCII//TRANSLIT', $str)
                            )
                        )
                    )
                ),
                $delimiter
            )
        );
    }
}
