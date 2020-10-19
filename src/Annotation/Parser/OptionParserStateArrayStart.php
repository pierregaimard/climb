<?php

namespace Climb\Annotation\Parser;

use Climb\Exception\AppException;

class OptionParserStateArrayStart extends AbstractOptionParserState
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

        // If array start character: increase array level
        if ($this->bot->getCharacter()->isArrayStart()) {
            $this->bot->increaseArrayLevel();
        }

        // If array end character: decrease array level.
        if ($this->isArrayEndCharacter()) {
            return;
        }

        // If end array character is missing
        $this->isArrayEndCharacterMissing();

        // If string declaration start character in array: goto option string declaration
        if ($this->bot->getCharacter()->isStringOptionStart()) {
            $this->bot->setState(OptionsParserBot::STATE_STRING_OPTION_START);
        }

        $this->bot->addCharacterToData();
    }

    /**
     * If array end character: decrease array level.
     *
     * @return bool
     */
    private function isArrayEndCharacter(): bool
    {
        if ($this->bot->getCharacter()->isArrayEnd()) {
            $this->bot->decreaseArrayLevel();

            if ($this->parseGlobalArrayEnd()) {
                return true;
            }
        }

        return false;
    }

    /**
     * If end of global array declaration: save and return to option end declaration.
     *
     * @return bool
     */
    private function parseGlobalArrayEnd(): bool
    {
        if ($this->bot->isGlobalArrayEnd()) {
            $this->bot->setState(OptionsParserBot::STATE_OPTION_END);

            if ($this->bot->isLastCharacter()) {
                $this->bot->setToSave();
            }

            return true;
        }

        return false;
    }

    /**
     * If end array character is missing
     *
     * @throws AppException
     */
    private function isArrayEndCharacterMissing(): void
    {
        if ($this->bot->isLastCharacter() && !$this->bot->isGlobalArrayEnd()) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                "Closing array is missing",
                sprintf('Annotation: "%s"', $this->annotation)
            );
        }
    }
}
