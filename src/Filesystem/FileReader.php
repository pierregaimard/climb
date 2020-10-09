<?php

namespace Framework3\Filesystem;

class FileReader
{
    public const TYPE_ARRAY  = 1;
    public const TYPE_STRING = 2;

    /**
     * @param string $path
     * @param int    $type
     *
     * @return array|false
     */
    public function getContent(string $path, int $type)
    {
        switch ($type) {
            case self::TYPE_ARRAY:
                return $this->getContentAsArray($path);

            case self::TYPE_STRING:
                return $this->getContentAsString($path);

            default:
                return false;
        }
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public function has($path)
    {
        return file_exists($path);
    }

    /**
     * @param string $path
     *
     * @return array|false
     */
    private function getContentAsArray(string $path)
    {
        return file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    /**
     * @param string $path
     *
     * @return string|false
     */
    private function getContentAsString(string $path)
    {
        return file_get_contents($path);
    }
}
