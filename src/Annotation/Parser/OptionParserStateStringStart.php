<?php

namespace Framework3\Annotation\Parser;

class OptionParserStateStringStart
{
    /**
     * @param OptionsParserBot $bot
     */
    public function parse(OptionsParserBot $bot): void
    {
        // If end of string declaration:
        if ($bot->getCharacter()->isStringOptionEnd($bot->getPrevCharacter())) {
            // If end of data: save
            if ($bot->isLastCharacter()) {
                $bot->setToSave();

                return;
            }

            // If valueType is array: return to array option declaration start
            if ($bot->isValueTypeArray()) {
                $bot->setState(OptionsParserBot::STATE_ARRAY_OPTION_START);
                $bot->addCharacterToData();

                return;
            }

            // Else return to option end declaration
            $bot->setState(OptionsParserBot::STATE_OPTION_END);

            return;
        }

        // If $char is escaped, remove escape character from data
        if (
            $bot->getCharacter()->isEscapedChar($bot->getPrevCharacter()->getChar()) &&
            $bot->getCharacter()->isStringOptionStart()
        ) {
            $bot->setData(substr($bot->getData(), 0, -1));
        }

        $bot->addCharacterToData();
    }
}
