<?php

namespace Climb\Annotation\Parser;

use Climb\Exception\AppException;

class OptionParserStateEnd
{
    /**
     * @param OptionsParserBot $bot
     * @param string           $annotation
     *
     * @throws AppException
     */
    public function parse(OptionsParserBot $bot, string $annotation): void
    {
        // Ignore whitespaces and linebreaks
        if ($bot->getCharacter()->isWhiteSpace() || $bot->getCharacter()->isLineBreak()) {
            return;
        }

        // If end of option declaration character: save and return to option declaration start
        if ($bot->getCharacter()->isOptionsSeparator()) {
            $bot->setState(OptionsParserBot::STATE_OPTION_START);

            $bot->setToSave();

            return;
        }

        // If affectation operator character: set data to key, save and return to option start declaration
        if ($bot->getCharacter()->isAffectationOperator()) {
            $bot->setState(OptionsParserBot::STATE_OPTION_START);
            $bot->setDataTypeToKey();

            $bot->setToSave();

            return;
        }

        // If is last character: save data
        if ($bot->isLastCharacter()) {
            $bot->setToSave();

            return;
        }

        // Else throws an exception
        throw new AppException(
            AppException::TYPE_ANNOTATION_PARSER,
            "Illegal character in option declaration",
            sprintf('Annotation: "%s"', $annotation)
        );
    }
}
