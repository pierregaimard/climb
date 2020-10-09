<?php

namespace Framework3\Env;

use Framework3\Filesystem\FileReader;

class EnvManager
{
    private const DEFAULT_NAME = 'env';

    /**
     * @var FileReader
     */
    protected FileReader $fileReader;

    /**
     * @var EnvParser
     */
    private EnvParser $parser;

    public function __construct(FileReader $fileReader)
    {
        $this->fileReader = $fileReader;
        $referenceParser  = new EnvReferenceParser();
        $this->parser     = new EnvParser($referenceParser);
    }

    /**
     * returns a combinaison of $_ENV data and custom .env
     *
     * _Note: $_ENV data can't be overwritten by custom .env data._
     *
     * @param string|null $fileDir
     * @param string|null $fileName
     *
     * @return array|null
     */
    public function getEnvData(string $fileDir = null, string $fileName = null): ?array
    {
        $globalEnvData = $this->getGlobalEnv();

        if ($fileDir !== null) {
            $file = $this->fileReader->getContent(
                $this->getFilePath($fileDir, $fileName),
                FileReader::TYPE_ARRAY
            );

            $customEnvData = $this->parser->getParsedData($file);

            return array_replace_recursive($customEnvData, $globalEnvData);
        }

        return $globalEnvData;
    }

    /**
     * returns absolute .env file path from fileDir & optional fileName
     *
     * _Note: if file name has not been set, DEFAULT_NAME is used._
     *
     * @param string $fileDir
     * @param string|null $fileName
     *
     * @return string
     */
    public function getFilePath(string $fileDir, string $fileName = null): string
    {
        $fileName = ($fileName) ? $fileName : self::DEFAULT_NAME;

        return $fileDir . "." . $fileName;
    }

    /**
     * returns an array of $_ENV data.
     *
     * @return array
     */
    private function getGlobalEnv(): array
    {
        return filter_input_array(INPUT_ENV);
    }
}
