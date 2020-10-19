<?php

namespace Climb\Annotation\Parser;

class AnnotationParserStateNameStart
{
    /**
     * @param AnnotationParserBot $bot
     */
    public function parse(AnnotationParserBot $bot): void
    {
        /*
         * Annotation first char must be uppercase alphabetic char
         * if true, goto annotation name declaration
         */
        if ($bot->getCharacter()->isAnnotationFirstChar()) {
            $bot->addCharacterToName();
            $bot->setState(AnnotationParserBot::STATE_NAME);

            return;
        }

        // if not, annotation is ignored: return to annotation declaration start
        $bot->setState(AnnotationParserBot::STATE_START);
    }
}
