<?php

namespace Framework3\Annotation\Parser;

class AnnotationParserStateStart
{
    /**
     * @param AnnotationParserBot $bot
     */
    public function parse(AnnotationParserBot $bot): void
    {
        if ($bot->getCharacter()->isAnnotationStart()) {
            // Annotations are ignored if not declared at the beginning of a line.
            if ($bot->hasPreventCharacter() && !$bot->getPrevCharacter()->isLineBreak()) {
                return;
            }

            // Else goto annotation name declaration start
            $bot->setState(AnnotationParserBot::STATE_NAME_START);

            return;
        }
    }
}
