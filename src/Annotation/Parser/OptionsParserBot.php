<?php

/**
 * Represents the state of the cursor during parsing of annotation options
 */

namespace Framework3\Annotation\Parser;

class OptionsParserBot extends AbstractParserBot
{
    /**
     * Beginning of an option declaration state
     */
    public const STATE_OPTION_START = 1;

    /**
     * End of an option declaration state
     */
    public const STATE_OPTION_END = 2;

    /**
     * Beginning of a string value declaration of an option.
     */
    public const STATE_STRING_OPTION_START = 3;

    /**
     * Beginning of an array value declaration.
     */
    public const STATE_ARRAY_OPTION_START = 4;

    /**
     * Indicates that the bot is parsing a key.
     */
    public const TYPE_KEY = 'type_key';

    /**
     * Indicates that the bot is parsing a value.
     */
    public const TYPE_VALUE = 'type_value';

    /**
     * @var string
     */
    private string $dataType;

    /**
     * @var int
     */
    private int $arrayLevel;

    /**
     * @var string|null
     */
    private ?string $valueType;

    /**
     * @var string|null
     */
    private ?string $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string|null
     */
    private ?string $data;

    /**
     * @var array
     */
    private array $options = [];

    /**
     * @param int $numberOfChars
     */
    public function __construct(int $numberOfChars)
    {
        parent::__construct(self::STATE_OPTION_START, $numberOfChars);
        $this->resetDataType();
        $this->resetArrayLevel();
        $this->resetValueType();
        $this->resetKey();
        $this->resetValue();
        $this->resetData();
    }

    public function setDataTypeToKey(): void
    {
        $this->dataType = self::TYPE_KEY;
    }

    public function setDataTypeToValue(): void
    {
        $this->dataType = self::TYPE_VALUE;
    }

    /**
     * Default data type state is "value".
     */
    public function resetDataType(): void
    {
        $this->dataType = self::TYPE_VALUE;
    }

    /**
     * @return bool
     */
    public function isDataTypeKey(): bool
    {
        return $this->dataType === self::TYPE_KEY;
    }

    /**
     * @return bool
     */
    public function isDataTypeValue(): bool
    {
        return $this->dataType === self::TYPE_VALUE;
    }

    /**
     * Sets the value type as a string type.
     */
    public function setValueTypeToString(): void
    {
        $this->valueType = OptionData::VAL_TYPE_STRING;
    }

    /**
     * @return bool
     */
    public function isValueTypeString(): bool
    {
        return $this->valueType === OptionData::VAL_TYPE_STRING;
    }

    /**
     * Sets the value type as an array type.
     */
    public function setValueTypeToArray(): void
    {
        $this->valueType = OptionData::VAL_TYPE_ARRAY;
    }

    /**
     * @return bool
     */
    public function isValueTypeArray(): bool
    {
        return $this->valueType === OptionData::VAL_TYPE_ARRAY;
    }

    /**
     * Sets the value type as an int type.
     */
    public function setValueTypeToInt(): void
    {
        $this->valueType = OptionData::VAL_TYPE_INT;
    }

    /**
     * @return bool
     */
    public function isValueTypeInt(): bool
    {
        return $this->valueType === OptionData::VAL_TYPE_INT;
    }

    /**
     * Sets the value type as a bool type.
     */
    public function setValueTypeToBool(): void
    {
        $this->valueType = OptionData::VAL_TYPE_BOOL;
    }

    /**
     * @return bool
     */
    public function isValueTypeBool(): bool
    {
        return $this->valueType === OptionData::VAL_TYPE_BOOL;
    }

    /**
     * Increases the array level in array declaration.
     *
     * Used to parse the array level and to check if an array close statement is missing.
     *
     * @example @Annotation(firstLevel={secondLevel={opt1, opt2}})
     */
    public function increaseArrayLevel(): void
    {
        $this->arrayLevel += 1;
    }

    /**
     * Increases the array level in array declaration.
     */
    public function decreaseArrayLevel(): void
    {
        $this->arrayLevel -= 1;
    }

    /**
     * Checks if all array statements have been closed.
     *
     * @return bool
     */
    public function isGlobalArrayEnd(): bool
    {
        return $this->arrayLevel === 0;
    }

    public function resetArrayLevel(): void
    {
        $this->arrayLevel = 0;
    }

    /**
     * @return string
     */
    public function getValueType(): string
    {
        return $this->valueType;
    }

    /**
     * @param string $valueType
     */
    public function setValueType(string $valueType): void
    {
        $this->valueType = $valueType;
    }

    public function resetValueType(): void
    {
        $this->valueType = null;
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string|null $key
     */
    public function setKey(?string $key): void
    {
        $this->key = $key;
    }
    
    public function resetKey(): void
    {
        $this->key = null;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function resetValue(): void
    {
        $this->value = null;
    }

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    public function addCharacterToData(): void
    {
        $this->data .= $this->getCharacter()->getChar();
    }

    /**
     * @param string|null $data
     */
    public function setData(?string $data): void
    {
        $this->data = $data;
    }

    public function resetData(): void
    {
        $this->data = null;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public function addArrayOption(): void
    {
        if ($this->key) {
            $this->options[$this->key] = $this->value;
            return;
        }

        $this->options[] = $this->value;
    }

    public function addObjectOption(): void
    {
        $this->options[$this->key] = new OptionData($this->valueType, $this->key, $this->value);
    }
}
