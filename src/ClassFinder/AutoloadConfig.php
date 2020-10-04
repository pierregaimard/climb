<?php

/**
 * This Class is used to retrieve Autoload configuration from composer.json
 */

namespace Framework3\ClassFinder;

use Framework3\Filesystem\FileReader;

class AutoloadConfig
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
        $this->reader = $reader;
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
}
