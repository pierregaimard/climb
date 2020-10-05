<?php

/**
 * Annotation Reader interface.
 */

namespace Framework3\Annotation;

interface ReaderInterface
{
    /**
     * returns an array of class annotations.
     *
     * If no annotation is found the method should return null.
     *
     * @return AnnotationInterface[]|null
     */
    public function getClassAnnotations();

    /**
     * Returns an array or a single annotation object for $annotation tag name.
     *
     * If true is passed to `$unique`, the method should just return the first occurrence of `$annotation`
     * If no annotation is found the method should return `null`.
     *
     * @param string    $annotation     Annotation tag name
     * @param bool      $unique         Option
     *
     * @return AnnotationInterface|AnnotationInterface[]|null
     */
    public function getClassAnnotation(string $annotation, $unique = false);

    /**
     * Returns an array of annotations objects for the class method `$method`.
     *
     * If no annotation is found the method should return `null`.
     * If method `$method` do not exists, the method should return `false`
     *
     * @param string $method    Class method name.
     *
     * @return AnnotationInterface[]|null|false
     */
    public function getMethodAnnotations(string $method);

    /**
     * Returns an array or an unique annotation object from class method `$method` and annotation tag name.
     *
     * If true is passed to `$unique`, the method should just return the first occurrence of `$annotation`
     * If no annotation is found the method should return `null`.
     * If method `$method` do not exists, the method should return `false`
     *
     * @param string    $method         Annotation method
     * @param string    $annotation     Annotation tag name
     * @param bool      $unique         Option.
     *
     * @return AnnotationInterface|AnnotationInterface[]|null|false
     */
    public function getMethodAnnotation(string $method, string $annotation, $unique = false);

    /**
     * Returns an array of all class methods annotation objects.
     *
     * If no annotation is found the method should return `null`.
     * Return array structure:
     *
     *  [
     *      methodName1 => [
     *          Annotation1,
     *          Annotation1,
     *          [...]
     *      ],
     *      methodName2 => [
     *          Annotation1,
     *          Annotation2,
     *          [...]
     *      ],
     *      [...]
     *  ]
     * Where every annotation object should implements `AnnotationInterface` interface.
     *
     * @return array|null
     */
    public function getMethodsAnnotations();

    /**
     * Returns an array of `$annotation` objects from all class methods.
     *
     * If true is passed to $unique, the method should just return the first occurrence of `$annotation`
     * If no annotation is found the method should return `null`.
     * Return array structure:
     *  [
     *      methodName1 => [
     *          Annotation1,
     *          Annotation1,
     *          [...]
     *      ],
     *      methodName2 => [
     *          Annotation1,
     *          Annotation2,
     *          [...]
     *      ],
     *      [...]
     *  ]
     * Where every annotation object should implements `AnnotationInterface` interface.
     *
     * @param string    $annotation     Annotation tag name
     * @param bool      $unique         Option
     *
     * @return array|null
     */
    public function getMethodsAnnotation(string $annotation, $unique = false);

    /**
     * Returns an array of annotations objects for class property `$property`.
     *
     * If no annotation is found the method should return `null`.
     * If class property `$property` do not exists, the method should return `false`
     *
     * @param string $property
     *
     * @return AnnotationInterface[]|null|false
     */
    public function getPropertyAnnotations(string $property);

    /**
     * Returns an array or an unique annotation object from class property `$property` and annotation tag name.
     *
     * If true is passed to `$unique`, the method should just return the first occurrence of `$annotation`
     * If no annotation is found the method should return `null`.
     * If the class property `$property` do not exists, the method should return `false`
     *
     * @param string    $property       Class property name
     * @param string    $annotation     Annotation tag name
     * @param bool      $unique         Option
     *
     * @return AnnotationInterface|AnnotationInterface[]|null|false
     */
    public function getPropertyAnnotation(string $property, string $annotation, $unique = false);

    /**
     * Returns an array of annotation objects for all class properties.
     *
     * If no annotation is found the method should return `null`.
     * Return array structure:
     *  [
     *      propertyName1 => [
     *          Annotation1,
     *          Annotation1,
     *          [...]
     *      ],
     *      propertyName2 => [
     *          Annotation1,
     *          Annotation2,
     *          [...]
     *      ],
     *      [...]
     *  ]
     * Where every annotation object should implements `AnnotationInterface` interface.
     *
     * @return array|null
     */
    public function getPropertiesAnnotations();

    /**
     * Returns an array of `$annotation` objects from all properties.
     *
     * If true is passed to $unique, the method should just return the first occurrence of `$annotation`
     * If no annotation is found the method should return `null`.
     * Return array structure:
     *  [
     *      propertyName1 => [
     *          Annotation1,
     *          Annotation1,
     *          [...]
     *      ],
     *      propertyName2 => [
     *          Annotation1,
     *          Annotation2,
     *          [...]
     *      ],
     *      [...]
     *  ]
     * Where every annotation object should implements `AnnotationInterface` interface.
     *
     * @param string    $annotation     Annotation tag name
     * @param bool      $unique         Option.
     *
     * @return array|null
     */
    public function getPropertiesAnnotation(string $annotation, bool $unique = false);
}
