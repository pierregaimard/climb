<?php

namespace Framework3\Env;

class EnvFileReader
{
    /**
     * @param string $path
     *
     * @return array
     */
    public function getFile(string $path): array
    {
        return file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
}
