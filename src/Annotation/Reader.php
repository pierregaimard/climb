<?php

/**
 * This class provides methods to retrieve annotations from a class.
 */

namespace Climb\Annotation;

use ReflectionClass;
use ReflectionException;
use Climb\Exception\AppException;

class Reader implements ReaderInterface
{
    /**
     * @var AnnotationManager AnnotationManager Service.
     */
    private AnnotationManager $annotationManager;

    /**
     * @var ReflectionClass
     */
    private ReflectionClass $reflectionClass;

    /**
     * Reader constructor.
     *
     * @param AnnotationManager $annotationManager
     * @param string $class
     *
     * @throws ReflectionException
     */
    public function __construct(AnnotationManager $annotationManager, string $class)
    {
        $this->annotationManager = $annotationManager;
        $this->reflectionClass   = new ReflectionClass($class);
    }

    /**
     * returns array of class annotations
     *
     * If no annotation is found the method returns null.
     *
     * @return AnnotationInterface[]|null
     *
     * @throws AppException
     */
    public function getClassAnnotations(): ?array
    {
        return $this->annotationManager->getAnnotations($this->reflectionClass->getDocComment());
    }

    /**
     * Returns an array or a single annotation object for $annotation tag name.
     *
     * If true is passed to $unique, the method will just return the first occurrence of $annotation
     * If no annotation is found the method returns null.
     *
     * @param string    $annotation     Annotation tag name
     * @param bool      $unique         Option
     *
     * @return AnnotationInterface|AnnotationInterface[]|null
     *
     * @throws AppException
     */
    public function getClassAnnotation(string $annotation, $unique = false)
    {
        if ($unique === true) {
            return $this->annotationManager->getAnnotation(
                $this->reflectionClass->getDocComment(),
                $annotation
            );
        }

        return $this->annotationManager->getAnnotations(
            $this->reflectionClass->getDocComment(),
            $annotation
        );
    }

    /**
     * Returns an array of annotations objects for $method.
     *
     * If no annotation is found the method returns null.
     * If method $method do not exists, the method returns false
     *
     * @param string $method    Class method name.
     *
     * @return AnnotationInterface[]|null|false
     *
     * @throws AppException
     * @throws ReflectionException
     */
    public function getMethodAnnotations(string $method)
    {
        if (!$this->reflectionClass->hasMethod($method)) {
            return false;
        }

        $reflectionMethod = $this->reflectionClass->getMethod($method);

        return $this->annotationManager->getAnnotations($reflectionMethod->getDocComment());
    }

    /**
     * Returns an array or an unique annotation object from method $method and annotation tag name.
     *
     * If true is passed to $unique, the method will just return the first occurrence of $annotation
     * If no annotation is found the method returns null.
     * If method $method do not exists, the method returns false
     *
     * @param string    $method         Annotation method
     * @param string    $annotation     Annotation tag name
     * @param bool      $unique         Option.
     *
     * @return AnnotationInterface|AnnotationInterface[]|null|false
     *
     * @throws ReflectionException
     * @throws AppException
     */
    public function getMethodAnnotation(string $method, string $annotation, $unique = false)
    {
        if (!$this->reflectionClass->hasMethod($method)) {
            return false;
        }

        $reflectionMethod = $this->reflectionClass->getMethod($method);

        if ($unique === true) {
            return $this->annotationManager->getAnnotation($reflectionMethod->getDocComment(), $annotation);
        }

        return $this->annotationManager->getAnnotations($reflectionMethod->getDocComment(), $annotation);
    }

    /**
     * Returns an array of all methods annotation objects.
     *
     * If no annotation is found the method returns null.
     * Array structure:
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
     *
     * @return array|null
     *
     * @throws AppException
     */
    public function getMethodsAnnotations(): ?array
    {
        $reflectionMethods = $this->reflectionClass->getMethods();

        if (empty($reflectionMethods)) {
            return null;
        }

        $annotations = [];

        foreach ($reflectionMethods as $reflectionMethod) {
            $result = $this->annotationManager->getAnnotations(
                $reflectionMethod->getDocComment()
            );

            if ($result !== null) {
                $annotations[$reflectionMethod->getName()] = $result;
            }
        }

        return $annotations;
    }

    /**
     * Returns an array of $annotation objects from all methods.
     *
     * If true is passed to $unique, the method will just return the first occurrence of $annotation
     * If no annotation is found the method returns null.
     * Array structure:
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
     *
     * @param string    $annotation     Annotation tag name
     * @param bool      $unique         Option
     *
     * @return array|null
     *
     * @throws AppException
     */
    public function getMethodsAnnotation(string $annotation, $unique = false): ?array
    {
        $reflectionMethods = $this->reflectionClass->getMethods();

        if (empty($reflectionMethods)) {
            return null;
        }

        return $this->getAnnotations($reflectionMethods, $annotation, $unique);
    }

    /**
     * Returns an array of annotations objects for $property.
     *
     * If no annotation is found the method returns null.
     * If property $property do not exists, the method returns false
     *
     * @param string $property
     *
     * @return AnnotationInterface[]|null|false
     *
     * @throws AppException
     * @throws ReflectionException
     */
    public function getPropertyAnnotations(string $property): ?array
    {
        if (!$this->reflectionClass->hasProperty($property)) {
            return false;
        }

        $reflectionProperty = $this->reflectionClass->getProperty($property);

        return $this->annotationManager->getAnnotations($reflectionProperty->getDocComment());
    }

    /**
     * Returns an array or an unique annotation object from property $property and annotation tag name.
     *
     * If true is passed to $unique, the method will just return the first occurrence of $annotation
     * If no annotation is found the method returns null.
     * If property $property do not exists, the method returns false
     *
     * @param string    $property       Class property name
     * @param string    $annotation     Annotation tag name
     * @param bool      $unique         Option
     *
     * @return AnnotationInterface|AnnotationInterface[]|null|false
     *
     * @throws AppException
     * @throws ReflectionException
     */
    public function getPropertyAnnotation(string $property, string $annotation, $unique = false)
    {
        if (!$this->reflectionClass->hasProperty($property)) {
            return false;
        }

        $reflectionProperty = $this->reflectionClass->getProperty($property);

        if ($unique === true) {
            return $this->annotationManager->getAnnotation($reflectionProperty->getDocComment(), $annotation);
        }

        return $this->annotationManager->getAnnotations($reflectionProperty->getDocComment(), $annotation);
    }

    /**
     * Returns an array of all properties annotation objects.
     *
     * If no annotation is found the method returns null.
     * Array structure:
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
     *
     * @return array|null
     *
     * @throws AppException
     */
    public function getPropertiesAnnotations(): ?array
    {
        $reflectionProperties = $this->reflectionClass->getProperties();

        if (empty($reflectionProperties)) {
            return null;
        }

        $annotations = [];

        foreach ($reflectionProperties as $reflectionProperty) {
            $result = $this->annotationManager->getAnnotations(
                $reflectionProperty->getDocComment()
            );

            if ($result !== null) {
                $annotations[$reflectionProperty->getName()] = $result;
            }
        }

        return $annotations;
    }

    /**
     * Returns an array of $annotation objects from all properties.
     *
     * If true is passed to $unique, the method will just return the first occurrence of $annotation
     * If no annotation is found the method returns null.
     * Array structure:
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
     *
     * @param string    $annotation     Annotation tag name
     * @param bool      $unique         Option.
     *
     * @return array|null
     *
     * @throws AppException
     */
    public function getPropertiesAnnotation(string $annotation, bool $unique = false)
    {
        $reflectionProperties = $this->reflectionClass->getProperties();

        if (empty($reflectionProperties)) {
            return null;
        }

        return $this->getAnnotations($reflectionProperties, $annotation, $unique);
    }

    /**
     * @param array  $reflections
     * @param string $annotation
     * @param bool   $unique
     *
     * @return array
     *
     * @throws AppException
     */
    private function getAnnotations(array $reflections, string $annotation, bool $unique)
    {
        $annotations = [];

        foreach ($reflections as $reflection) {
            $result = ($unique === true) ?
                    $this->annotationManager->getAnnotation(
                        $reflection->getDocComment(),
                        $annotation
                    ) :
                    $this->annotationManager->getAnnotations(
                        $reflection->getDocComment(),
                        $annotation
                    );

            if ($result !== null) {
                $annotations[$reflection->getName()] = $result;
            }
        }

        return $annotations;
    }
}
