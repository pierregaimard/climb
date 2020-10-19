<?php

namespace Climb\Routing;

use Climb\Annotation\ReaderManagerInterface;
use Climb\Exception\AppException;

class Router implements RouterInterface
{
    /**
     * @var ReaderManagerInterface
     */
    private ReaderManagerInterface $readerManager;

    /**
     * @var RouteParser
     */
    private RouteParser $routeParser;

    /**
     * Array of controllers classes names.
     *
     * @var array|null
     */
    private ?array $controllers;

    /**
     * @var RouteAnnotationContainer
     */
    private RouteAnnotationContainer $annotationContainer;

    /**
     * @param ReaderManagerInterface   $readerManager
     * @param RouteParser              $routeParser
     * @param RouteControllerContainer $controllersContainer
     * @param RouteAnnotationContainer $annotationContainer
     *
     * @throws AppException
     */
    public function __construct(
        ReaderManagerInterface $readerManager,
        RouteParser $routeParser,
        RouteControllerContainer $controllersContainer,
        RouteAnnotationContainer $annotationContainer
    ) {
        $this->readerManager       = $readerManager;
        $this->routeParser         = $routeParser;
        $this->annotationContainer = $annotationContainer;
        $this->controllers         = $controllersContainer->getControllersClasses();
    }

    /**
     * returns route object or false if requestPath doesn't matches any route.
     *
     * @param string $requestPath
     *
     * @return RouteInterface|false
     */
    public function getRoute(string $requestPath)
    {
        if ($this->controllers === null) {
            return false;
        }

        foreach ($this->controllers as $controller) {
            // get routes annotation declaration
            $routeAnnotations = $this->annotationContainer->getAnnotations($controller);

            // if no route declaration in controller: continue.
            if ($routeAnnotations === null) {
                continue;
            }

            $route = $this->searchRoute($routeAnnotations, $requestPath, $controller);
            if ($route instanceof Route) {
                return $route;
            }
        }

        return false;
    }

    /**
     * @param array  $routeAnnotations
     * @param string $requestPath
     * @param string $controller
     *
     * @return Route|null
     */
    private function searchRoute(array $routeAnnotations, string $requestPath, string $controller): ?Route
    {
        foreach ($routeAnnotations as $method => $routeAnnotation) {
            if ($this->routeParser->isVariableRoutePath($routeAnnotation->getPath())) {
                if ($this->routeParser->isRouteMatch($routeAnnotation, $requestPath)) {
                    return new Route(
                        $controller,
                        $method,
                        $this->routeParser->getRouteData($routeAnnotation, $requestPath)
                    );
                }
            }

            if ($routeAnnotation->getPath() === $requestPath) {
                return new Route($controller, $method);
            }
        }

        return null;
    }
}
