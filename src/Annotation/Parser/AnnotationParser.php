<?php

/**
 * Annotation parser
 *
 * Set of functions used to parse docComment and returns formatted annotation data.
 */

namespace Climb\Annotation\Parser;

use Climb\Exception\AppException;

class AnnotationParser
{
    /**
     * @var OptionParser
     */
    private OptionParser $optionParser;

    /**
     * @var AnnotationParserTools
     */
    private AnnotationParserTools $parserTools;

    /**
     * @var AnnotationCharacterParser
     */
    private AnnotationCharacterParser $characterParser;

    /**
     * @param OptionParser              $optionParser
     * @param AnnotationParserTools     $parserTools
     * @param AnnotationCharacterParser $characterParser
     */
    public function __construct(
        OptionParser $optionParser,
        AnnotationParserTools $parserTools,
        AnnotationCharacterParser $characterParser
    ) {
        $this->optionParser    = $optionParser;
        $this->parserTools     = $parserTools;
        $this->characterParser = $characterParser;
    }

    /**
     * Parses docComment and returns formatted annotations data.
     *
     * This function parses the docComment character by character.
     * it is possible to give an annotation name do retrieve only annotations
     * who have $annotation name.
     *
     * @param string      $docComment ReflectionClass docComment.
     * @param string|null $annotation Annotation name to retrieve.
     *
     * @return AnnotationData[]|null
     *
     * @throws AppException
     */
    public function parseAnnotationsData(string $docComment, $annotation = null): ?array
    {
        // Returns docComment without declaration slashes, stars and intermediate whitespaces
        $comment            = $this->parserTools->removeDocCommentDeclarationChars($docComment);
        $commentAsCharArray = str_split($comment);
        $bot                = new AnnotationParserBot(count($commentAsCharArray));

        foreach ($commentAsCharArray as $character) {
            $bot->increaseCounter();
            $bot->setCharacter(new AnnotationCharacter($character));

            $this->characterParser->parseCharacter($bot);

            if ($bot->isToSave()) {
                // Save all annotations if `$annotation` is not set or just `$annotation` name
                $this->storeAnnotationData($bot, $annotation);

                $bot->setPrevName();
                $bot->resetName();
                $bot->resetValue();
                $bot->resetToSave();
            }

            $bot->setPrevCharacter();
        }

        return $bot->getAnnotationsData();
    }

    /**
     * Save all annotations if `$annotation` is not set or just `$annotation` name
     *
     * @param AnnotationParserBot $bot
     * @param string|null         $annotation
     *
     * @throws AppException
     */
    private function storeAnnotationData(AnnotationParserBot $bot, string $annotation = null): void
    {
        if ($annotation === null || $annotation === $bot->getName()) {
            $this->isInvalidAnnotationName($bot);

            $annotationData = new AnnotationData($bot->getName());

            if ($bot->getValue() !== '') {
                $annotationData->setOptions(
                    $this->optionParser->getOptionsData($bot->getName(), trim($bot->getValue()))
                );
            }

            $bot->addAnnotationData($annotationData);
        }
    }

    /**
     * Invalid annotation name (uses default regex)
     *
     * @param AnnotationParserBot $bot
     *
     * @throws AppException
     */
    private function isInvalidAnnotationName(AnnotationParserBot $bot): void
    {
        if (!$this->parserTools->isAnnotationNameValid($bot->getName())) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_PARSER,
                "Invalid Annotation name declaration",
                sprintf('Annotation: "%s"', $bot->getName())
            );
        }
    }
}
