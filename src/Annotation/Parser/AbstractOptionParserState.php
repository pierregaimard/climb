<?php

namespace Climb\Annotation\Parser;

class AbstractOptionParserState
{
    /**
     * @var OptionsParserBot
     */
    protected OptionsParserBot $bot;

    /**
     * @var string
     */
    protected string $annotation;
}
