<?php

/**
 * This class represents the state of the cursor during parsing of an annotation
 */

namespace Framework3\Annotation\Parser;

class AnnotationParserBot extends AbstractParserBot
{
    /**
     * Beginning of an annotation declaration
     */
    public const STATE_START = 1;

    /**
     * Beginning of the annotation name declaration
     */
    public const STATE_NAME_START = 2;

    /**
     * Annotation name declaration
     */
    public const STATE_NAME = 3;

    /**
     * End of the annotation name declaration
     */
    public const STATE_NAME_END = 4;

    /**
     * Beginning of the annotation options declaration
     */
    public const STATE_OPTIONS_START = 5;

    /**
     * End of the annotation options declaration
     */
    public const STATE_OPTIONS_END = 6;

    /**
     * Beginning of an annotation string value declaration ("my string value")
     *
     * This state allow to use special characters.
     * It is possible to use " character in string value declaration, but it must be escaped.
     * e.g. "my name is: \"Joe\", and i like code"
     */
    public const STATE_STRING_OPTION_START = 7;

    /**
     * @var string|null
     */
    private ?string $optionsState;

    /**
     * @var AnnotationData[]|null
     */
    private array $annotationsData = [];

    /**
     * @var string|null
     */
    private ?string $name;

    /**
     * @var string|null
     */
    private ?string $prevName;

    /**
     * @var string|null
     */
    private ?string $value;

    /**
     * @param int $numberOfChar
     */
    public function __construct(int $numberOfChar)
    {
        parent::__construct(self::STATE_START, $numberOfChar);
        $this->resetName();
        $this->resetValue();
    }

    /**
     * Sets annotation options declaration to open state
     */
    public function openOptionsDeclaration(): void
    {
        $this->optionsState = 'open';
    }

    /**
     * Sets annotation options declaration to closed state
     */
    public function closeOptionsDeclaration(): void
    {
        $this->optionsState = 'closed';
    }

    /**
     * Checks if the annotation options declaration is open.
     *
     * @return bool
     */
    public function isOptionsDeclarationOpen(): bool
    {
        return $this->optionsState === 'open';
    }

    /**
     * @return AnnotationData[]
     */
    public function getAnnotationsData(): array
    {
        return $this->annotationsData;
    }

    /**
     * @param AnnotationData $data
     */
    public function addAnnotationData(AnnotationData $data): void
    {
        $this->annotationsData[] = $data;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function addCharacterToName(): void
    {
        $this->name .= $this->getCharacter()->getChar();
    }

    public function resetName(): void
    {
        $this->name = null;
    }

    /**
     * @return string|null
     */
    public function getPrevName(): ?string
    {
        return $this->prevName;
    }

    public function setPrevName(): void
    {
        $this->prevName = $this->name;
    }

    public function resetPrevName(): void
    {
        $this->prevName = null;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function addCharacterToValue(): void
    {
        $this->value .= $this->getCharacter()->getChar();
    }

    public function resetValue(): void
    {
        $this->value = null;
    }
}
