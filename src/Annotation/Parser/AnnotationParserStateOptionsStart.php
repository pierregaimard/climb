<?php

namespace Framework3\Annotation\Parser;

use Framework3\Exception\AppException;

class AnnotationParserStateOptionsStart
{
    /**
     * @param AnnotationParserBot $bot
     *
     * @throws AppException
     */
    public function parse(AnnotationParserBot $bot): void
    {
        // String declaration (ignore special characters)
        if ($bot->getCharacter()->isStringOptionStart()) {
            $bot->setState(AnnotationParserBot::STATE_STRING_OPTION_START);
        }

        // End of annotation declaration: save annotation and goto annotation options end
        if ($bot->getCharacter()->isOptionsEnd()) {
            $bot->setState(AnnotationParserBot::STATE_OPTIONS_END);

            $bot->setToSave();

            return;
        }

        if ($bot->getCharacter()->isAnnotationStart() && $bot->isOptionsDeclarationOpen()) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                "Closing parenthesis is missing",
                sprintf('Annotation: "%s"', $bot->getName())
            );
        }

        $bot->addCharacterToValue();
    }
}
