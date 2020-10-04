<?php

/**
 * Used to retrieve an array of classes from a given namespace.
 */

namespace Framework3\ClassFinder;

use Framework3\Exception\AppException;
use Framework3\Filesystem\DirectoryReader;

class Finder implements FinderInterface
{
    /**
     * Used to retrieve PSR-4 autoload configuration.
     *
     * @var AutoloadConfig
     */
    private AutoloadConfig $autoloadConfig;

    /**
     * @var DirectoryReader
     */
    private DirectoryReader $dirReader;

    /**
     * @var string
     */
    private string $baseDir;

    /**
     * @param AutoloadConfig  $autoloadConfig
     * @param DirectoryReader $dirReader
     * @param string          $baseDir
     */
    public function __construct(AutoloadConfig $autoloadConfig, DirectoryReader $dirReader, string $baseDir)
    {
        $this->autoloadConfig = $autoloadConfig;
        $this->dirReader = $dirReader;
        $this->baseDir = $baseDir;
    }

    /**
     * Returns an array of fully qualified namespaced classes from a given namespace
     *
     * Arguments:
     *
     *  -   $namespace:
     *      given namespace to scan
     *
     *  -   $getSubNamespaceClasses:
     *      If this option is set to true, the method will scan the sub directories too
     *      and retrieves the sub-classes
     *
     *  -   $suffix:
     *      if a suffix is given, the method will just retrieves the classes who contains the given suffix.
     *      e.g. $suffix = "Controller" Returns for example `Controller\AdminController`
     *      but not `Controller\User`
     *
     * @param string      $namespace  Given namespace to scan
     * @param bool        $scanSubDir Allow to retrieve classes from sub directories.
     * @param string|null $suffix     Class suffix
     *
     * @return array|null
     *
     * @throws AppException
     */
    public function getClassesList(string $namespace, $scanSubDir = true, string $suffix = null): ?array
    {
        $relativeFilesPaths = $this->scan(
            $this->baseDir . DIRECTORY_SEPARATOR . $this->getDirPathFromNamespace($namespace),
            null,
            $scanSubDir
        );

        if ($relativeFilesPaths === null) {
            return null;
        }

        $classList = [];

        foreach ($relativeFilesPaths as $relativeFilePath) {
            if (substr($relativeFilePath, -4) !== '.php') {
                continue;
            }

            if ($suffix) {
                $suffixLength = strlen($suffix);
                if (substr($relativeFilePath, -($suffixLength + 4), $suffixLength) !== $suffix) {
                    continue;
                }
            }

            $classList[] = $this->getFinalClassName($namespace, $relativeFilePath);
        }

        return $classList;
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
    private function scan(string $baseDir, string $subDir = null, $scanSubDirs = true): ?array
    {
        $dir = ($subDir) ? $baseDir . DIRECTORY_SEPARATOR . $subDir : $baseDir;
        $list = array_diff($this->dirReader->scan($dir), ['.', '..']);
        $classList = [];

        foreach ($list as $item) {
            if ($this->dirReader->isDir($dir . DIRECTORY_SEPARATOR . $item)) {
                if (!$scanSubDirs) {
                    continue;
                }

                $childSubDir = ($subDir) ? $subDir . DIRECTORY_SEPARATOR . $item : $item;
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

    /**
     * converts a namespace to a relative file path (relative to the project base directory).
     *
     * @example namespace "App\Controller" will be converted to file path "src/Controller"
     *
     * @param $namespace
     *
     * @return string|string[]|null
     *
     * @throws AppException
     */
    private function getDirPathFromNamespace($namespace)
    {
        foreach ($this->autoloadConfig->getPsr4Config() as $baseNamespace => $basePath) {
            $pattern = '#^' . str_replace('\\', '\\\\', $baseNamespace) . '#';

            if (preg_match($pattern, $namespace)) {
                return str_replace('\\', '/', preg_replace($pattern, $basePath, $namespace));
            }
        }

        throw new AppException(
            AppException::TYPE_CLASS_FINDER,
            'Invalid namespace exception',
            sprintf('The base of namespace "%s" do not exists in psr-4 composer.json autoload', $namespace)
        );
    }

    /**
     * Returns a fully qualified class namespace from a base namespace and a relative file path.
     *
     * @param string $namespace         Given namespace from public getClassList() method
     * @param string $relativeFilePath  File path relative to project base dir.
     *
     * @return string
     */
    private function getFinalClassName(string $namespace, string $relativeFilePath): string
    {
        return
            $namespace .
            '\\' .
            str_replace(
                DIRECTORY_SEPARATOR,
                '\\',
                substr($relativeFilePath, 0, -4)
            )
        ;
    }
}
