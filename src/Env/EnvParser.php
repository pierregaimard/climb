<?php

namespace Framework3\Env;

use RuntimeException;

class EnvParser
{
    /**
     * @var EnvReferenceParser
     */
    private EnvReferenceParser $referenceParser;

    /**
     * @param EnvReferenceParser $referenceParser
     */
    public function __construct(EnvReferenceParser $referenceParser)
    {
        $this->referenceParser = $referenceParser;
    }

    /**
     * @param array $file
     *
     * @return array
     */
    public function getParsedData(array $file): array
    {
        $rawData = $this->parseFile($file);
        return $this->referenceParser->setReferences($rawData);
    }

    /**
     * @param array $file
     *
     * @return array
     */
    public function parseFile(array $file): array
    {
        $envData = [];

        foreach ($file as $line) {
            if (!$this->isComment($line)) {
                $envData = array_merge($envData, $this->parseLine($line));
            }
        }

        return $envData;
    }

    /**
     * @param string $line
     *
     * @return bool
     */
    private function isComment(string $line): bool
    {
        return substr(trim($line), 0, 1) === '#';
    }

    /**
     * @param string $line
     *
     * @return array
     *
     * @throws RuntimeException
     */
    private function parseLine(string $line): array
    {
        preg_match(
            '#^([a-zA-Z]+(_?[a-zA-Z0-9]+)*)=("?([.\-:@\#(){}$\'a-zA-Z0-9_/\\\\]*?)"?)$#',
            $line,
            $matches
        );

        if (empty($matches)) {
            throw new RuntimeException(
                sprintf(
                    '.env Parser error: Illegal variable declaration in env file: "%s"',
                    $line
                )
            );
        }

        $key = trim($matches[1]);
        $value = ($matches[3] !== "") ? $matches[3] : null;

        if ($value && substr($value, 0, 1) === '"') {
            if (substr($value, -1, 1) !== '"') {
                throw new RuntimeException(
                    sprintf(
                        ".env Parser error: Double quote is missing at the end in env file: '%s'",
                        $line
                    )
                );
            }

            $value = $matches[4];
        }

        return [$key => $value];
    }
}
