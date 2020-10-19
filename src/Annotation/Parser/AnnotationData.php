<?php

/**
 * Represents an annotation data declaration.
 *
 * It contains
 *  - the annotation name
 *  - an optional array of OptionData objects where OptionData represents the declaration of an annotation option.
 */

namespace Climb\Annotation\Parser;

class AnnotationData
{
    /**
     * Name of the annotation
     *
     * @var string
     */
    private string $name;

    /**
     * Array of annotation options declaration.
     *
     * @var OptionData[]|null
     */
    private ?array $options;

    /**
     * AnnotationData constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return OptionData[]|null
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * Checks if the annotation declaration contains options.
     *
     * @return bool
     */
    public function hasOptions(): bool
    {
        return $this->options !== null;
    }

    /**
     * @param OptionData[] $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}
