<?php

/**
 * this class provides Lib\AnnotationReader instances
 */

namespace Framework3\Annotation;

use ReflectionException;

class ReaderManager implements ReaderManagerInterface
{
    /**
     * @var AnnotationManager
     */
    private AnnotationManager $annotationManager;

    /**
     * ReaderManager constructor.
     *
     * @param AnnotationManager $annotationManager
     */
    public function __construct(AnnotationManager $annotationManager)
    {
        $this->annotationManager = $annotationManager;
    }

    /**
     * returns an annotation reader instance for a class
     *
     * @param $class
     *
     * @return ReaderInterface
     *
     * @throws ReflectionException
     */
    public function getReader($class): ReaderInterface
    {
        return new Reader($this->annotationManager, $class);
    }
}
