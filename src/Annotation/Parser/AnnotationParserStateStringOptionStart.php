<?php

namespace Climb\Annotation\Parser;

use Climb\Exception\AppException;

class AnnotationParserStateStringOptionStart
{
    /**
     * @param AnnotationParserBot $bot
     *
     * @throws AppException
     */
    public function parse(AnnotationParserBot $bot): void
    {
        // Can not use linebreak in string value of an option
        if ($bot->getCharacter()->isLineBreak()) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                "Quote is missing or illegal line break is used in string declaration",
                sprintf('Annotation: "%s"', $bot->getName())
            );
        }

        $bot->addCharacterToValue();

        // End of string declaration: return to annotation options declaration
        if ($bot->getCharacter()->isStringOptionEnd($bot->getPrevCharacter()->getChar())) {
            $bot->setState(AnnotationParserBot::STATE_OPTIONS_START);
        }

        if ($bot->isLastCharacter()) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                "Closing parenthesis is missing",
                sprintf('Annotation: "%s"', $bot->getName())
            );
        }
    }
}
