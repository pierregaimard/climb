<?php

namespace Framework3\Annotation\Parser;

use Framework3\Config\ConfigBag;
use Framework3\Exception\AppException;

class OptionParserTools
{
    /**
     * @var array
     */
    private array $config;

    /**
     * @param ConfigBag $config
     *
     * @throws AppException
     */
    public function __construct(ConfigBag $config)
    {
        $this->config = $config->get('CONFIG', true);
    }

    /**
     * Checks if the annotation option key name matches width the config regex.
     *
     * @param string $key
     *
     * @return bool
     *
     * @throws AppException
     */
    public function isOptionKeyValid(string $key): bool
    {
        return preg_match($this->getOptionKeyValidRegex(), $key) !== 0;
    }

    /**
     * Checks if $value is an int value and do not begin width a zero.
     *
     * @param string $value
     *
     * @return bool
     */
    public function isOptionIntVal(string $value): bool
    {
        return preg_match('#^[1-9][0-9]*$#', $value) !== 0;
    }

    /**
     * Checks if $value matches width bool true|false keywords
     *
     * @param string $value
     *
     * @return bool
     */
    public function isOptionBoolVal(string $value): bool
    {
        return preg_match('#^true|false$#', $value) !== 0;
    }

    /**
     * Returns typehint int value of $value
     *
     * @param string $value
     *
     * @return int
     */
    public function getOptionIntVal(string $value): int
    {
        return intval($value);
    }

    /**
     * Returns typehint bool value from $value
     *
     * @param string $value
     *
     * @return bool|null
     */
    public function getOptionBoolVal(string $value): ?bool
    {
        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        return null;
    }

    /**
     * Retrieves the regex of a valid annotation option key declaration.
     *
     * During parsing, if the annotation name don't match width the regex, an exception will be throws.
     * If this parameter is missing in config file, the method will throws an exception.
     *
     * @return string
     *
     * @throws AppException
     */
    private function getOptionKeyValidRegex(): string
    {
        if (!array_key_exists('OPTION_KEY_VALID_REGEX', $this->config)) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                'Annotation configuration parameter is missing',
                'Name: "OPTION_KEY_VALID_REGEX". Check the "lib/annotation" configuration file.'
            );
        }

        return $this->config['OPTION_KEY_VALID_REGEX'];
    }
}
