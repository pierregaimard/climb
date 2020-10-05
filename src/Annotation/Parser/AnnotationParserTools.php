<?php

namespace Framework3\Annotation\Parser;

use Framework3\Config\ConfigBag;
use Framework3\Exception\AppException;

class AnnotationParserTools
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
     * Retrieves the regex of a valid annotation name declaration.
     *
     * During parsing, if the annotation name don't match width the regex, an exception will be throws.
     * If this parameter is missing in config file, the method will throws an exception.
     *
     * @return string
     *
     * @throws AppException
     */
    private function getAnnotationNameValidRegex(): string
    {
        if (!array_key_exists('ANNOTATION_NAME_VALID_REGEX', $this->config)) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                'Annotation configuration parameter is missing',
                'Name: "ANNOTATION_NAME_VALID_REGEX". Check the "lib/annotation" configuration file.'
            );
        }

        return $this->config['ANNOTATION_NAME_VALID_REGEX'];
    }

    /**
     * returns docComment without declaration slashes, stars and intermediate whitespaces
     *
     * @param string $docComment
     *
     * @return string|null
     */
    public function removeDocCommentDeclarationChars(string $docComment): ?string
    {
        // remove start/end docComment declaration
        $comment = substr($docComment, 3, -2);

        // remove intermediate stars and intermediate whitespaces and keep linebreaks
        return trim(preg_replace('/[ ]*\n[ ]*\*[ ]*/', "\n", $comment));
    }

    /**
     * Checks if the annotation name matches width the config regex.
     *
     * @param string $name
     *
     * @return bool
     *
     * @throws AppException
     */
    public function isAnnotationNameValid(string $name): bool
    {
        return preg_match($this->getAnnotationNameValidRegex(), $name) !== 0;
    }
}
