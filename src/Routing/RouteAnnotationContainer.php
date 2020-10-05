<?php

namespace Framework3\Routing;

use Framework3\Annotation\ReaderManagerInterface;

class RouteAnnotationContainer
{
    /**
     * @var ReaderManagerInterface
     */
    private ReaderManagerInterface $readerManager;

    /**
     * @var array
     */
    private array $container = [];

    /**
     * @param ReaderManagerInterface   $readerManager
     */
    public function __construct(ReaderManagerInterface $readerManager)
    {
        $this->readerManager   = $readerManager;
    }

    /**
     * Returns an array of route annotation objects from a given controller class name.
     *
     * if a controller have already been parsed the stored value is return.
     *
     * @param string $controllerClass
     *
     * @return array|null
     */
    public function getAnnotations(string $controllerClass): ?array
    {
        if (array_key_exists($controllerClass, $this->container)) {
            return $this->container[$controllerClass];
        }

        $reader = $this->readerManager->getReader($controllerClass);

        $this->container[$controllerClass] = $reader->getMethodsAnnotation(
            "Route",
            true
        );

        return $this->container[$controllerClass];
    }
}
