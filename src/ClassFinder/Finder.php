<?php

/**
 * Used to retrieve an array of classes from a given namespace.
 */

namespace Framework3\ClassFinder;

use Framework3\Exception\AppException;

class Finder implements FinderInterface
{
    /**
     * Used to retrieve PSR-4 autoload configuration.
     *
     * @var AutoloadTools
     */
    private AutoloadTools $autoloadTools;

    /**
     * @var Scanner
     */
    private Scanner $scanner;

    /**
     * @var string
     */
    private string $baseDir;

    /**
     * @param AutoloadTools $autoloadTools
     * @param Scanner       $scanner
     * @param string        $baseDir
     */
    public function __construct(AutoloadTools $autoloadTools, Scanner $scanner, string $baseDir)
    {
        $this->autoloadTools = $autoloadTools;
        $this->scanner = $scanner;
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
        $relativeFilesPaths = $this->scanner->scan(
            $this->baseDir . DIRECTORY_SEPARATOR . $this->autoloadTools->getDirPathFromNamespace($namespace),
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
