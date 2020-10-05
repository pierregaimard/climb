<?php

/**
 * ParserBot represents the state of the cursor during parsing of an annotation
 */

namespace Framework3\Annotation\Parser;

abstract class AbstractParserBot
{
    /**
     * State of the Bot.
     *
     * @var string
     */
    private string $state;

    /**
     * @var AbstractCharacter
     */
    private AbstractCharacter $character;

    /**
     * @var AbstractCharacter|null
     */
    private ?AbstractCharacter $prevCharacter;

    /**
     * @var bool
     */
    private bool $toSave;

    /**
     * Total number of characters to parse.
     *
     * @var int
     */
    private int $numberOfChars;

    /**
     * Counter of parsed characters
     *
     * @var int
     */
    private int $counter;

    /**
     * AbstractParserBot constructor.
     *
     * @param string $state
     * @param int    $numberOfChars
     */
    public function __construct(string $state, int $numberOfChars)
    {
        $this->setState($state);
        $this->resetToSave();
        $this->numberOfChars = $numberOfChars;
        $this->resetCounter();
    }

    /**
     * @param string
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return AbstractCharacter
     */
    public function getCharacter(): AbstractCharacter
    {
        return $this->character;
    }

    /**
     * @param AbstractCharacter $character
     */
    public function setCharacter(AbstractCharacter $character): void
    {
        $this->character = $character;
    }

    /**
     * @return AbstractCharacter|null
     */
    public function getPrevCharacter(): ?AbstractCharacter
    {
        return $this->prevCharacter;
    }

    public function setPrevCharacter(): void
    {
        $this->prevCharacter = $this->character;
    }

    /**
     * @return bool
     */
    public function hasPreventCharacter(): bool
    {
        return isset($this->prevCharacter);
    }

    /**
     * @return bool
     */
    public function isToSave(): bool
    {
        return $this->toSave;
    }

    public function setToSave(): void
    {
        $this->toSave = true;
    }

    public function resetToSave(): void
    {
        $this->toSave = false;
    }

    /**
     * @return int
     */
    public function getNumberOfChars(): int
    {
        return $this->numberOfChars;
    }

    /**
     * @param int $numberOfChars
     */
    public function setNumberOfChars(int $numberOfChars): void
    {
        $this->numberOfChars = $numberOfChars;
    }

    public function increaseCounter(): void
    {
        $this->counter ++;
    }

    public function resetCounter(): void
    {
        $this->counter = 0;
    }

    /**
     * @return int
     */
    public function getCounter(): int
    {
        return $this->counter;
    }

    /**
     * @return bool
     */
    public function isLastCharacter(): bool
    {
        return $this->counter === $this->numberOfChars;
    }
}
