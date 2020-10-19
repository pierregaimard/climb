<?php

namespace Climb\Filesystem;

class DirectoryReader
{
    /**
     * @param string $dir
     *
     * @return array|false
     */
    public function scan(string $dir)
    {
        if (!$this->isDir($dir)) {
            return false;
        }

        return scandir($dir);
    }

    /**
     * @param string $dir
     *
     * @return bool
     */
    public function isDir(string $dir)
    {
        return is_dir($dir);
    }
}