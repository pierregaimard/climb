<?php

namespace Framework3\FileReader;

class FileReader
{
    public const TYPE_ENV = 1;
    public const TYPE_CONFIG = 2;

    /**
     * @param string $path
     * @param int    $type
     *
     * @return array|false
     */
    public function getFile(string $path, int $type)
    {
        switch ($type) {
            case self::TYPE_ENV:
                return $this->getEnvFile($path);

            case self::TYPE_CONFIG:
                return $this->getConfigFile($path);

            default:
                return false;
        }
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public function hasFile($path)
    {
        return file_exists($path);
    }

    /**
     * @param string $path
     *
     * @return array|false
     */
    private function getEnvFile(string $path)
    {
        return file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    /**
     * @param string $path
     *
     * @return string|false
     */
    private function getConfigFile(string $path)
    {
        return file_get_contents($path);
    }
}
