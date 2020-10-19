<?php

/**
 * This class is used to return hydrated annotation objects from docComment.
 */

namespace Climb\Annotation;

use Climb\Annotation\Parser\AnnotationData;
use Climb\Annotation\Parser\AnnotationParser;
use Climb\Config\ConfigBag;
use Climb\Exception\AppException;

class AnnotationManager
{
    /**
     * Key used to declare class name in lib/annotation configuration file.
     */
    private const ANNOTATION_CLASS = 'class';

    /**
     * Array of annotations declaration. Retrieves from "ANNOTATIONS" key in lib/annotation configuration file.
     *
     * @var array
     */
    private array $annotations;

    /**
     * AnnotationParser service
     *
     * @var AnnotationParser
     */
    private AnnotationParser $annotationParser;

    /**
     * AnnotationManager constructor.
     *
     * @param ConfigBag         $config             from lib/annotation config => ['ANNOTATION'] key.
     * @param AnnotationParser  $annotationParser
     *
     * @throws AppException
     */
    public function __construct(ConfigBag $config, AnnotationParser $annotationParser)
    {
        $this->annotations      = $config->get('ANNOTATIONS', true);
        $this->annotationParser = $annotationParser;
    }

    /**
     * returns an annotation object from docComment and annotation tag name.
     *
     * @param string $docComment
     * @param string $annotation Annotation tag name. e.g. "Route"
     *
     * @return AnnotationInterface|null
     *
     * @throws AppException
     */
    public function getAnnotation(string $docComment, string $annotation): ?AnnotationInterface
    {
        $annotationsData = $this->annotationParser->parseAnnotationsData($docComment, $annotation);
        if (empty($annotationsData)) {
            return null;
        }

        return $this->getHydratedAnnotation($annotationsData[0], $annotation);
    }

    /**
     * Returns an array of annotation objects.
     *
     * If $annotation is set, the method will just return an array of $annotation objects
     * If no annotation is found in docComment, the function will return null.
     *
     * @param string      $docComment
     * @param string|null $annotation Annotation tag name. e.g. "Route"
     *
     * @return AnnotationInterface[]|null
     *
     * @throws AppException
     */
    public function getAnnotations(string $docComment, string $annotation = null): ?array
    {
        $annotationsData = $this->annotationParser->parseAnnotationsData($docComment, $annotation);
        if (empty($annotationsData)) {
            return null;
        }

        $annotations = [];

        foreach ($annotationsData as $annotationData) {
            $annotations[] = $this->getHydratedAnnotation($annotationData, $annotation);
        }

        return $annotations;
    }

    /**
     * Returns the annotation class name from lib/annotation configuration file.
     *
     * An exception will be throws if
     *  - $annotation is not found in configuration file
     *  - 'class' statement is missing for this annotation configuration.
     *
     * @param string $annotation    Annotation tag name. e.g. "Route"
     *
     * @return string
     *
     * @throws AppException
     */
    private function getAnnotationClass(string $annotation): string
    {
        if (!array_key_exists($annotation, $this->annotations)) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_MANAGER_EXCEPTION,
                'Annotation declaration is missing in configuration file',
                sprintf('Annotation name: "%s". See lib/annotation configuration file.', $annotation)
            );
        }

        if (!array_key_exists(self::ANNOTATION_CLASS, $this->annotations[$annotation])) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_MANAGER_EXCEPTION,
                'Annotation "class" statement is missing in configuration file',
                sprintf('Annotation name: "%s". See lib/annotation configuration file.', $annotation)
            );
        }

        return $this->annotations[$annotation][self::ANNOTATION_CLASS];
    }

    /**
     * Returns an annotation object from annotation class declaration found in lib/annotation config file.
     *
     * The method throws an exception if class $class do not exists.
     *
     * @param $class
     *
     * @return AnnotationInterface
     *
     * @throws AppException
     */
    private function getAnnotationObject($class): AnnotationInterface
    {
        if (!class_exists($class)) {
            throw new AppException(
                AppException::TYPE_ANNOTATION_MANAGER_EXCEPTION,
                'Annotation class do not exists',
                sprintf('Annotation: "%s"', $class)
            );
        }

        return new $class();
    }

    /**
     * instantiate, hydrate and returns an annotation object
     *
     * @param AnnotationData    $annotationData     Annotation data retrieves from annotation parser
     * @param string|null       $annotation         Annotation tag name. e.g. "Route"
     *
     * @return AnnotationInterface
     *
     * @throws AppException
     */
    private function getHydratedAnnotation(AnnotationData $annotationData, ?string $annotation): AnnotationInterface
    {
        if (!$annotation) {
            $annotation = $annotationData->getName();
        }

        $class      = $this->getAnnotationClass($annotation);
        $annotation = $this->getAnnotationObject($class);

        foreach ($annotationData->getOptions() as $option) {
            $set = 'set' . $option->getKey();
            $annotation->$set($option->getValue());
        }

        return $annotation;
    }
}
