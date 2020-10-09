<?php

/**
 * used to store one parsed annotation data with the correct type of value.
 */

namespace Framework3\Annotation\Parser;

class OptionData
{
    public const VAL_TYPE_INT    = 'int';
    public const VAL_TYPE_STRING = 'string';
    public const VAL_TYPE_BOOL   = 'bool';
    public const VAL_TYPE_ARRAY  = 'array';

    /**
     * Represents the option key.
     *
     * @var string
     */
    private string $key;

    /**
     * Type of the option value.
     *
     * @var string
     */
    private string $valueType;

    /**
     * @var int
     */
    private int $valueInt;

    /**
     * @var string
     */
    private string $valueString;

    /**
     * @var bool
     */
    private bool $valueBool;

    /**
     * @var array
     */
    private array $valueArray;

    /**
     * OptionData constructor.
     *
     * @param string $valueType
     * @param string $key
     * @param $value
     */
    public function __construct(string $valueType, string $key, $value)
    {
        $this->valueType = $valueType;
        $this->key       = $key;
        $this->setValue($value);
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

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @param int $valueInt
     *
     * @return bool
     */
    public function setValueInt(int $valueInt): bool
    {
        if (!is_int($valueInt)) {
            return false;
        }

        $this->valueInt = $valueInt;

        return true;
    }

    /**
     * @param string $valueString
     *
     * @return bool
     */
    public function setValueString(string $valueString): bool
    {
        if (!is_string($valueString)) {
            return false;
        }

        $this->valueString = $valueString;

        return true;
    }

    /**
     * @param bool $valueBool
     *
     * @return bool
     */
    public function setValueBool(bool $valueBool): bool
    {
        if (!is_bool($valueBool)) {
            return false;
        }

        $this->valueBool = $valueBool;

        return true;
    }

    /**
     * @param array $valueArray
     *
     * @return bool
     */
    public function setValueArray(array $valueArray): bool
    {
        if (!is_array($valueArray)) {
            return false;
        }

        $this->valueArray = $valueArray;

        return true;
    }

    /**
     * Sets the value in terms of value type.
     *
     * @param $value
     */
    public function setValue($value): void
    {
        switch ($this->valueType) {
            case self::VAL_TYPE_INT:
                $this->setValueInt($value);
                break;

            case self::VAL_TYPE_STRING:
                $this->setValueString($value);
                break;

            case self::VAL_TYPE_BOOL:
                $this->setValueBool($value);
                break;

            case self::VAL_TYPE_ARRAY:
                $this->setValueArray($value);
                break;

            default:
                break;
        }
    }

    /**
     * Returns the value in terms of value type.
     *
     * @return array|bool|int|string|null
     */
    public function getValue()
    {
        switch ($this->valueType) {
            case self::VAL_TYPE_INT:
                return $this->valueInt;

            case self::VAL_TYPE_STRING:
                return $this->valueString;

            case self::VAL_TYPE_BOOL:
                return $this->valueBool;

            case self::VAL_TYPE_ARRAY:
                return $this->valueArray;

            default:
                return null;
        }
    }
}
