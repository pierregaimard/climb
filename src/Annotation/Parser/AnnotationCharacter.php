<?php

/**
 * Represents a character during parsing of an annotation
 */

namespace Framework3\Annotation\Parser;

class AnnotationCharacter extends AbstractCharacter
{
    /**
     * Checks if $char represents the beginning of an annotation declaration
     *
     * @return bool
     */
    public function isAnnotationStart(): bool
    {
        return $this->getChar() === '@';
    }

    /**
     * Checks if $char is the first character of an annotation name.
     *
     * Must be a upper case letter [A-Z]
     *
     * @return bool
     */
    public function isAnnotationFirstChar(): bool
    {
        return preg_match('#^[A-Z]$#', $this->getChar()) !== 0;
    }

    /**
     * Checks if $char represents the beginning of annotation options declaration
     *
     * Annotations options must be enclosed in parentheses
     *
     * @example @Route(name="my_route")
     *
     * @return bool
     */
    public function isOptionsStart(): bool
    {
        return $this->getChar() === '(';
    }

    /**
     * Checks if $char represents the end of annotation options declaration.
     *
     * @return bool
     */
    public function isOptionsEnd(): bool
    {
        return $this->getChar() === ')';
    }
}
