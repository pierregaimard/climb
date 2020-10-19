<?php

namespace Climb\Annotation\Parser;

use Climb\Exception\AppException;

class OptionCharacterParser
{
    /**
     * @var OptionParserStateStart
     */
    private OptionParserStateStart $parserStart;

    /**
     * @var OptionParserStateArrayStart
     */
    private OptionParserStateArrayStart $parserArrayStart;

    /**
     * @var OptionParserStateStringStart
     */
    private OptionParserStateStringStart $parserStringStart;

    /**
     * @var OptionParserStateEnd
     */
    private OptionParserStateEnd $parserEnd;

    public function __construct(
        OptionParserStateStart $parserStart,
        OptionParserStateArrayStart $parserArrayStart,
        OptionParserStateStringStart $parserStringStart,
        OptionParserStateEnd $parserEnd
    ) {
        $this->parserStart       = $parserStart;
        $this->parserArrayStart  = $parserArrayStart;
        $this->parserStringStart = $parserStringStart;
        $this->parserEnd         = $parserEnd;
    }

    /**
     * @param OptionsParserBot $bot
     * @param string           $annotation
     *
     * @throws AppException
     */
    public function parseCharacter(OptionsParserBot $bot, string $annotation)
    {
        switch ($bot->getState()) {
            // Option declaration start
            case OptionsParserBot::STATE_OPTION_START:
                $this->parserStart->parse($bot, $annotation);
                break;

            // Array option declaration start
            case OptionsParserBot::STATE_ARRAY_OPTION_START:
                $this->parserArrayStart->parse($bot, $annotation);
                break;

            // String option declaration start
            case OptionsParserBot::STATE_STRING_OPTION_START:
                $this->parserStringStart->parse($bot);
                break;

            // Option declaration end
            case OptionsParserBot::STATE_OPTION_END:
                $this->parserEnd->parse($bot, $annotation);
                break;
        }
    }
}
