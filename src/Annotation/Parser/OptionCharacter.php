<?php

/**
 * This class represents a character in the annotation options declaration.
 */

namespace Framework3\Annotation\Parser;

class OptionCharacter extends AbstractCharacter
{
    /**
     * Checks if $char represents an option separator.
     *
     * @example @Route(name="my_name", path="/my-route")
     *
     * @return bool
     */
    public function isOptionsSeparator(): bool
    {
        return $this->getChar() === ',';
    }

    /**
     * Checks if $char represents an affectation operator
     *
     * @example option="value"
     *
     * @return bool
     */
    public function isAffectationOperator(): bool
    {
        return $this->getChar() === '=';
    }

    /**
     * Checks if $char is escaped.
     *
     * This function must use the prevent character during parsing.
     *
     * @param $prevChar
     *
     * @return bool
     */
    public function isEscapedChar($prevChar): bool
    {
        return $prevChar === '\\';
    }

    /**
     * Checks if $char represents the beginning of an array option declaration
     *
     * @example @MyAnnotation(tab={123,435})
     *
     * @return bool
     */
    public function isArrayStart(): bool
    {
        return $this->getChar() === '{';
    }

    /**
     * Checks if $char represents the end of an array option declaration
     *
     * @return bool
     */
    public function isArrayEnd(): bool
    {
        return $this->getChar() === '}';
    }
}
