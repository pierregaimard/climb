<?php

namespace Framework3\Env;

use RuntimeException;

class EnvFileReader
{
    /**
     * @param string $path
     *
     * @return array|false
     *
     * @throws RuntimeException
     */
    public function getFile(string $path): array
    {
        if (!file_exists($path)) {
            throw new RuntimeException(
                sprintf('.env Manager exception: The env file "%s" do not exists', $path)
            );
        }

        return file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
}
