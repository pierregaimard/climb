<?php

namespace Climb\Annotation\Parser;

use Climb\Exception\AppException;

class AnnotationParserStateOptionsEnd
{
    /**
     * @param AnnotationParserBot $bot
     *
     * @throws AppException
     */
    public function parse(AnnotationParserBot $bot): void
    {
        // End of annotation declaration: return to start annotation declaration
        if ($bot->getCharacter()->isLineBreak()) {
            $bot->setState(AnnotationParserBot::STATE_START);

            return;
        }

        // Ignore white spaces
        if ($bot->getCharacter()->isWhiteSpace()) {
            return;
        }

        if ($bot->getCharacter()->isAnnotationStart()) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                "You can only declare one annotation per line",
                sprintf('Annotation: "%s"', $bot->getName())
            );
        }

        throw new AppException(
            AppException::TYPE_ANNOTATION_PARSER,
            "Illegal character on same line as an annotation",
            sprintf('Annotation: "%s"', $bot->getPrevName())
        );
    }
}
