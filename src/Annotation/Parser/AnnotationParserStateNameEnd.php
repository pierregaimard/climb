<?php

namespace Framework3\Annotation\Parser;

use Framework3\Exception\AppException;

class AnnotationParserStateNameEnd
{
    /**
     * @param AnnotationParserBot $bot
     *
     * @throws AppException
     */
    public function parse(AnnotationParserBot $bot): void
    {
        // Exceptions
        if (!$bot->getPrevCharacter()->isLineBreak() && !$bot->getCharacter()->isOptionsStart()) {
            $this->isPrevLineBreakOrOptionStart($bot);
        }

        // Ignore white spaces
        if ($bot->getCharacter()->isWhiteSpace()) {
            return;
        }
        // options declaration start: goto annotation options declaration start
        if ($bot->getCharacter()->isOptionsStart()) {
            $bot->setState(AnnotationParserBot::STATE_OPTIONS_START);
            $bot->openOptionsDeclaration();

            return;
        }
        // end of annotation declaration: save annotation and goto annotation name start
        if ($bot->getCharacter()->isAnnotationStart()) {
            $bot->setState(AnnotationParserBot::STATE_NAME_START);

            $bot->setToSave();

            return;
        }
    }

    /**
     * @param AnnotationParserBot $bot
     *
     * @throws AppException
     */
    private function isPrevLineBreakOrOptionStart(AnnotationParserBot $bot): void
    {
        if ($bot->getCharacter()->isAnnotationStart()) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                "You can only declare one annotation per line",
                sprintf('Annotation: "%s"', $bot->getName())
            );
        }

        if (!$bot->getCharacter()->isWhiteSpace()) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                "Opening parenthesis is missing or illegal character on same line as an annotation",
                sprintf('Annotation: "%s"', $bot->getName())
            );
        }
    }
}
