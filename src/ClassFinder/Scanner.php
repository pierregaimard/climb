<?php

namespace Framework3\ClassFinder;

use Framework3\Filesystem\DirectoryReader;

class Scanner
{
    /**
     * @var DirectoryReader
     */
    private DirectoryReader $dirReader;

    /**
     * @param DirectoryReader $dirReader
     */
    public function __construct(DirectoryReader $dirReader)
    {
        $this->dirReader = $dirReader;
    }

    /**
     * scans a directory and returns an array of absolute files path from a base directory.
     *
     * If $subDir is given, it will be added to $baseDir ($baseDir . '/' . $subDir)
     * to set the directory who will be scan.
     *
     *      For `$baseDir = "/var/www/project"`
     *      and `$subDir = "public"`
     *      the final scan dir will be `/var/www/project/public`
     *
     * @param string      $baseDir      Absolute Base directory path
     * @param string|null $subDir       Sub directory (will be added to $baseDir if given)
     * @param bool        $scanSubDirs  If set to true, the method will retrieve the sub directories files.
     *
     * @return array|null
     */
    public function scan(string $baseDir, string $subDir = null, $scanSubDirs = true): ?array
    {
        $dir       = ($subDir) ? $baseDir . DIRECTORY_SEPARATOR . $subDir : $baseDir;
        $list      = array_diff($this->dirReader->scan($dir), ['.', '..']);
        $classList = [];

        foreach ($list as $item) {
            if ($this->dirReader->isDir($dir . DIRECTORY_SEPARATOR . $item)) {
                if (!$scanSubDirs) {
                    continue;
                }

                $childSubDir  = ($subDir) ? $subDir . DIRECTORY_SEPARATOR . $item : $item;
                $subClassList = $this->scan($baseDir, $childSubDir);

                continue;
            }

            $classList[] = ($subDir) ?  $subDir . DIRECTORY_SEPARATOR . $item : $item;
        }

        if (!empty($subClassList)) {
            $classList = array_merge($classList, $subClassList);
        }

        return (!empty($classList)) ? $classList : null;
    }
}
