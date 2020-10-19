<?php

namespace Climb\Annotation\Parser;

use Climb\Exception\AppException;

class OptionParser
{
    /**
     * This option is used when parsing annotation options.
     * It returns parsed data as an array.
     */
    private const RETURN_TYPE_OBJECT = 1;

    /**
     * This option is used when parsing annotation options.
     * It returns parsed data as an array of OptionData objects.
     */
    private const RETURN_TYPE_ARRAY = 2;

    /**
     * @var OptionParserTools
     */
    private OptionParserTools $tools;

    /**
     * @var OptionCharacterParser
     */
    private OptionCharacterParser $characterParser;

    public function __construct(
        OptionParserTools $tools,
        OptionCharacterParser $characterParser
    ) {
        $this->tools           = $tools;
        $this->characterParser = $characterParser;
    }

    /**
     * returns parsed annotation options as an array of OptionData objects.
     *
     * @param string $annotationName
     * @param string $optionsRawData
     *
     * @return OptionData[]
     *
     * @throws AppException
     */
    public function getOptionsData(string $annotationName, string $optionsRawData): array
    {
        return $this->parseOptionsData(
            $annotationName,
            $optionsRawData,
            self::RETURN_TYPE_OBJECT
        );
    }

    /**
     * Parses an annotation options declaration and returns formatted options data.
     *
     * Annotation options declarations is in parentheses
     * e.g. @Route(option1, [option2="value2", ...])
     *
     * @param string $annotationName    Annotation name
     * @param string $optionsValue      Annotation options declaration in parentheses @Annotation(optionsValue)
     * @param string $returnType        Array or OptionData array
     *
     * @return array|OptionData[]
     *
     * @throws AppException
     */
    private function parseOptionsData(string $annotationName, string $optionsValue, string $returnType): array
    {
        $charactersArray = str_split($optionsValue);            // options as an array of each character.
        $bot             = new OptionsParserBot(count($charactersArray));   // Options parser bot.

        foreach ($charactersArray as $character) {
            $bot->increaseCounter();
            $bot->setCharacter(new OptionCharacter($character));

            $this->characterParser->parseCharacter($bot, $annotationName);

            if ($bot->isToSave()) {
                // $data is a key
                if ($bot->isDataTypeKey()) {
                    $this->setDataAsKey($bot, $annotationName);
                }

                // $data is a value
                if ($bot->isDataTypeValue()) {
                    $this->setDataAsValue($bot, $annotationName);

                    $this->storeValue($bot, $annotationName, $returnType);

                    // Reset vars.
                    $bot->resetKey();
                    $bot->resetValue();
                    $bot->resetData();
                }

                // Reset bot and save states
                $bot->resetDataType();
                $bot->resetValueType();
                $bot->resetToSave();
            }

            $bot->setPrevCharacter();
        }

        return $bot->getOptions();
    }

    /**
     * @param OptionsParserBot $bot
     * @param string           $annotation
     *
     * @throws AppException
     */
    private function setDataAsKey(OptionsParserBot $bot, string $annotation): void
    {
        $bot->setKey($bot->getData());

        if (!$this->tools->isOptionKeyValid($bot->getKey())) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                "Illegal option key declaration",
                sprintf('Annotation: "%s", Key name:"%s"', $annotation, $bot->getKey())
            );
        }

        $bot->resetData();
    }

    /**
     * @param OptionsParserBot $bot
     * @param string           $annotation
     *
     * @throws AppException
     */
    private function setDataAsValue(OptionsParserBot $bot, string $annotation): void
    {
        $bot->setValue($bot->getData());

        if (!$bot->isValueTypeString()) {
            if ($bot->isValueTypeArray()) {
                $bot->setValue(
                    $this->parseOptionsData($annotation, $bot->getValue(), self::RETURN_TYPE_ARRAY)
                );

                return;
            }
            if ($this->tools->isOptionIntVal($bot->getValue())) {
                $bot->setValue($this->tools->getOptionIntVal($bot->getValue()));
                $bot->setValueTypeToInt();

                return;
            }

            if ($this->tools->isOptionBoolVal($bot->getValue())) {
                $bot->setValue($this->tools->getOptionBoolVal($bot->getValue()));
                $bot->setValueTypeToBool();

                return;
            }

            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                "Illegal option value declaration",
                sprintf('Annotation: "%s", Value: "%s"', $annotation, $bot->getValue())
            );
        }
    }

    /**
     * @param OptionsParserBot $bot
     * @param string           $annotation
     * @param string           $returnType
     *
     * @throws AppException
     */
    private function storeValue(OptionsParserBot $bot, string $annotation, string $returnType): void
    {
        // Stores the option in terms of the return type who have been asked
        switch ($returnType) {
            case self::RETURN_TYPE_ARRAY:
                $bot->setArrayOption();

                return;

            case self::RETURN_TYPE_OBJECT:
                if ($bot->getKey() === null) {
                    throw new AppException(
                        AppException::TYPE_ANNOTATION_PARSER,
                        "Annotation option must have key",
                        sprintf('Annotation: "%s"', $annotation)
                    );
                }

                $bot->setObjectOption();

                return;
        }
    }
}
