<?php

namespace Framework3\Annotation\Parser;

use Framework3\Exception\AppException;

class OptionParserStateStart extends AbstractOptionParserState
{
    /**
     * @param OptionsParserBot $bot
     * @param string           $annotation
     *
     * @throws AppException
     */
    public function parse(OptionsParserBot $bot, string $annotation): void
    {
        $this->bot        = $bot;
        $this->annotation = $annotation;

        /*
         * If white space, goto option end.
         * White space is not authorized in option key or int|bool val
         */
        if ($this->isWhiteSpaceOrLineBreak()) {
            return;
        }

        // String value declaration: goto value string declaration
        if ($this->isStringValueStart()) {
            return;
        }

        // Array value declaration start
        if ($this->isArrayValueStart()) {
            return;
        }

        // If $char is affectation operator $data is key: save key and stay in option start declaration
        if ($this->isAffectationOperator()) {
            return;
        }

        // If options separator: save and stay in option declaration start
        if ($this->isOptionSeparator()) {
            return;
        }

        $bot->addCharacterToData();

        // If end of declaration: save
        if ($bot->isLastCharacter()) {
            $bot->setToSave();
        }
    }

    /**
     * If white space, goto option end.
     *
     * white space is not authorized in option key or int|bool val
     *
     * @return bool
     */
    private function isWhiteSpaceOrLineBreak(): bool
    {
        if ($this->bot->getCharacter()->isWhiteSpace() || $this->bot->getCharacter()->isLineBreak()) {
            if ($this->bot->getData() !== null) {
                $this->bot->setState(OptionsParserBot::STATE_OPTION_END);
            }

            return true;
        }

        return false;
    }

    /**
     * String value start character: goto value string declaration
     *
     * @return bool
     *
     * @throws AppException
     */
    private function isStringValueStart(): bool
    {
        if ($this->bot->getCharacter()->isStringOptionStart()) {
            if ($this->bot->getData() !== null) {
                throw new AppException(
                    AppException::TYPE_ANNOTATION_PARSER,
                    "Illegal character in option declaration or affectation operator is missing",
                    sprintf('Annotation: "%s"', $this->annotation)
                );
            }

            $this->bot->setState(OptionsParserBot::STATE_STRING_OPTION_START);
            $this->bot->setValueTypeToString();
            $this->bot->setDataTypeToValue();

            return true;
        }

        return false;
    }

    /**
     * Array value start: goto array option start
     *
     * @return bool
     */
    private function isArrayValueStart(): bool
    {
        if ($this->bot->getCharacter()->isArrayStart()) {
            $this->bot->setState(OptionsParserBot::STATE_ARRAY_OPTION_START);
            $this->bot->setDataTypeToValue();
            $this->bot->setValueTypeToArray();
            $this->bot->increaseArrayLevel();

            return true;
        }

        return false;
    }

    /**
     * If $char is an affectation operator: $data is a key => Save key and stay in option start declaration
     *
     * @return bool
     *
     * @throws AppException
     */
    private function isAffectationOperator(): bool
    {
        if ($this->bot->getCharacter()->isAffectationOperator()) {
            if ($this->bot->getData() === null) {
                throw new AppException(
                    AppException::TYPE_ANNOTATION_PARSER,
                    "key is missing in annotation option declaration",
                    sprintf('Annotation: "%s"', $this->annotation)
                );
            }

            $this->bot->setDataTypeToKey();
            $this->bot->setToSave();

            return true;
        }

        return false;
    }

    /**
     * If options separator: save and stay in option declaration start
     *
     * @return bool
     *
     * @throws AppException
     */
    private function isOptionSeparator(): bool
    {
        if ($this->bot->getCharacter()->isOptionsSeparator()) {
            if ($this->bot->isLastCharacter()) {
                throw new AppException(
                    AppException::TYPE_ANNOTATION_PARSER,
                    "illegal empty options declaration at the end",
                    sprintf('Annotation: "%s"', $this->annotation)
                );
            }

            if ($this->bot->getData() === null) {
                throw new AppException(
                    AppException::TYPE_ANNOTATION_PARSER,
                    "illegal empty option declaration",
                    sprintf('Annotation: "%s"', $this->annotation)
                );
            }

            $this->bot->setToSave();

            return true;
        }

        return false;
    }
}
