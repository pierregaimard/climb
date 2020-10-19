<?php

/**
 * Represents a character during parsing of an annotation.
 */

namespace Climb\Annotation\Parser;

abstract class AbstractCharacter
{
    /**
     * @var string
     */
    private string $char;

    /**
     * AbstractCharacter constructor.
     *
     * @param $char
     */
    public function __construct($char)
    {
        $this->char = $char;
    }

    /**
     * @return string
     */
    public function getChar(): string
    {
        return $this->char;
    }

    /**
     * Checks if $char is the beginning of a string value declaration.
     *
     * @return bool
     */
    public function isStringOptionStart(): bool
    {
        return $this->char === '"';
    }

    /**
     * Checks if $char is the end of a string value declaration.
     *
     * @param string $prevChar
     *
     * @return bool
     */
    public function isStringOptionEnd(string $prevChar): bool
    {
        return $this->char === '"' && $prevChar !== '\\';
    }

    /**
     * @return bool
     */
    public function isWhiteSpace(): bool
    {
        return $this->char === ' ';
    }

    /**
     * @return bool
     */
    public function isLineBreak(): bool
    {
        return $this->char === "\n";
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->char;
    }
}
