<?php

/**
 * This Class is used to retrieve Autoload configuration from composer.json
 */

namespace Framework3\ClassFinder;

use Framework3\Exception\AppException;
use Framework3\Filesystem\FileReader;

class AutoloadTools
{
    /**
     * @var FileReader
     */
    private FileReader $reader;

    /**
     * @var string
     */
    private string $baseDir;

    /**
     * @param FileReader $reader
     * @param string     $baseDir
     */
    public function __construct(FileReader $reader, string $baseDir)
    {
        $this->reader  = $reader;
        $this->baseDir = $baseDir;
    }

    /**
     * @return array|null
     */
    public function getPsr4Config(): ?array
    {
        $path = $this->baseDir . DIRECTORY_SEPARATOR . 'composer.json';
        $composerData = json_decode(
            $this->reader->getContent($path, FileReader::TYPE_STRING),
            true
        );

        return $composerData['autoload']['psr-4'];
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
    public function getDirPathFromNamespace($namespace)
    {
        foreach ($this->getPsr4Config() as $baseNamespace => $basePath) {
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
}
