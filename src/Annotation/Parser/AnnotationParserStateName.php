<?php

namespace Framework3\Annotation\Parser;

use Framework3\Exception\AppException;

class AnnotationParserStateName
{
    /**
     * @param AnnotationParserBot $bot
     *
     * @throws AppException
     */
    public function parse(AnnotationParserBot $bot): void
    {
        // End of name declaration (annotation name can not contains whitespace)
        if ($bot->getCharacter()->isLineBreak() || $bot->getCharacter()->isWhiteSpace()) {
            $bot->setState(AnnotationParserBot::STATE_NAME_END);

            return;
        }

        // Start of options declaration: goto annotation options start
        if ($bot->getCharacter()->isOptionsStart()) {
            $bot->setState(AnnotationParserBot::STATE_OPTIONS_START);
            $bot->openOptionsDeclaration();

            return;
        }

        // if options start char is missing: throws exception
        if ($bot->getCharacter()->isOptionsEnd()) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                "Opening parenthesis is missing in annotation options declaration",
                sprintf('After annotation: "%s"', $bot->getPrevName())
            );
        }

        $bot->addCharacterToName();

        // if end of docComment: store
        if ($bot->isLastCharacter()) {
            $bot->setToSave();
        }
    }
}
