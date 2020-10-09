<?php

namespace Framework3\Annotation\Parser;

use Framework3\Exception\AppException;

class AnnotationCharacterParser
{
    /**
     * @var AnnotationParserStateStart
     */
    private AnnotationParserStateStart $parserStart;

    /**
     * @var AnnotationParserStateNameStart
     */
    private AnnotationParserStateNameStart $parserNameStart;

    /**
     * @var AnnotationParserStateName
     */
    private AnnotationParserStateName $parserName;

    /**
     * @var AnnotationParserStateNameEnd
     */
    private AnnotationParserStateNameEnd $parserNameEnd;

    /**
     * @var AnnotationParserStateOptionsStart
     */
    private AnnotationParserStateOptionsStart $parserOptionsStart;

    /**
     * @var AnnotationParserStateStringOptionStart
     */
    private AnnotationParserStateStringOptionStart $parserStrOptionStart;

    /**
     * @var AnnotationParserStateOptionsEnd
     */
    private AnnotationParserStateOptionsEnd $parserOptionsEnd;

    /**
     * @param AnnotationParserStateStart             $parserStart
     * @param AnnotationParserStateNameStart         $parserNameStart
     * @param AnnotationParserStateName              $parserName
     * @param AnnotationParserStateNameEnd           $parserNameEnd
     * @param AnnotationParserStateOptionsStart      $parserOptionsStart
     * @param AnnotationParserStateStringOptionStart $parserStrOptionStart
     * @param AnnotationParserStateOptionsEnd        $parserOptionsEnd
     */
    public function __construct(
        AnnotationParserStateStart $parserStart,
        AnnotationParserStateNameStart $parserNameStart,
        AnnotationParserStateName $parserName,
        AnnotationParserStateNameEnd $parserNameEnd,
        AnnotationParserStateOptionsStart $parserOptionsStart,
        AnnotationParserStateStringOptionStart $parserStrOptionStart,
        AnnotationParserStateOptionsEnd $parserOptionsEnd
    ) {
        $this->parserStart          = $parserStart;
        $this->parserNameStart      = $parserNameStart;
        $this->parserName           = $parserName;
        $this->parserNameEnd        = $parserNameEnd;
        $this->parserOptionsStart   = $parserOptionsStart;
        $this->parserStrOptionStart = $parserStrOptionStart;
        $this->parserOptionsEnd     = $parserOptionsEnd;
    }

    /**
     * @param AnnotationParserBot $bot
     *
     * @throws AppException
     */
    public function parseCharacter(AnnotationParserBot $bot)
    {
        switch ($bot->getState()) {
            // Annotation declaration start
            case AnnotationParserBot::STATE_START:
                $this->parserStart->parse($bot);
                break;

            // Annotation name declaration start
            case AnnotationParserBot::STATE_NAME_START:
                $this->parserNameStart->parse($bot);
                break;

            // Annotation name declaration
            case AnnotationParserBot::STATE_NAME:
                $this->parserName->parse($bot);
                break;

            // Annotation name declaration end
            case AnnotationParserBot::STATE_NAME_END:
                $this->parserNameEnd->parse($bot);
                break;

            // Annotation options declaration start
            case AnnotationParserBot::STATE_OPTIONS_START:
                $this->parserOptionsStart->parse($bot);
                break;

            // String option declaration start
            case AnnotationParserBot::STATE_STRING_OPTION_START:
                $this->parserStrOptionStart->parse($bot);
                break;

            // Annotation options declaration end
            case AnnotationParserBot::STATE_OPTIONS_END:
                $this->parserOptionsEnd->parse($bot);
            // no break
        }
    }
}
