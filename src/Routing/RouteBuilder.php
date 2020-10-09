<?php

namespace Framework3\Routing;

use Framework3\Exception\AppException;
use Framework3\Routing\Annotation\Route as RouteAnnotation;

class RouteBuilder implements RouteBuilderInterface
{
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
     * @var RouteBuilderUtils
     */
    private RouteBuilderUtils $utils;

    /**
     * @param RouteParser              $routeParser
     * @param RouteControllerContainer $container
     * @param RouteAnnotationContainer $annotationContainer
     * @param RouteBuilderUtils        $utils
     *
     * @throws AppException
     */
    public function __construct(
        RouteParser $routeParser,
        RouteControllerContainer $container,
        RouteAnnotationContainer $annotationContainer,
        RouteBuilderUtils $utils
    ) {
        $this->routeParser         = $routeParser;
        $this->controllers         = $container->getControllersClasses();
        $this->annotationContainer = $annotationContainer;
        $this->utils               = $utils;
    }

    /**
     * Returns a route path from route name and an array of variable data if present.
     *
     * This Method must return a string of route path,
     * or throws an exception if no route matches width the given name.
     *
     * @param string        $routeName  Name given in route declaration.
     * @param array|null    $data       Array of data for the route. (if route path contains variable data)
     *
     * @return string
     *
     * @throws AppException
     */
    public function getRoutePath(string $routeName, array $data = null): string
    {
        foreach ($this->controllers as $controller) {
            $routeAnnotations = $this->annotationContainer->getAnnotations($controller);

            if ($routeAnnotations === null) {
                continue;
            }

            foreach ($routeAnnotations as $routeAnnotation) {
                // if route name matches:
                if ($routeAnnotation->getName() === $routeName) {
                    return $this->setRoutePath($controller, $routeAnnotation, $routeName, $data);
                }
            }
        }

        /*
         * if $routeName do not matches width any route declaration,
         * the method throws an exception.
         */
        throw new AppException(
            AppException::TYPE_ROUTER,
            "SetRoutePath Exception.",
            sprintf(
                'No route found for name: "%"',
                $routeName,
            )
        );
    }

    /**
     * @param string          $controller
     * @param RouteAnnotation $routeAnnotation
     * @param string          $routeName
     * @param array|null      $data
     *
     * @return string
     *
     * @throws AppException
     */
    private function setRoutePath(
        string $controller,
        RouteAnnotation $routeAnnotation,
        string $routeName,
        array $data = null
    ): string {
        /*
         * if route path do not contains variable fragment,
         * the complete path is directly returned.
         */
        if (!$this->routeParser->isVariableRoutePath($routeAnnotation->getPath())) {
            return $routeAnnotation->getPath();
        }

        // Checks if `$data` is an array.
        $this->utils->isDataArray($data, $routeName, $controller);

        // else building the route path
        $explodePath = explode('/', substr($routeAnnotation->getPath(), 1));
        $path        = '';

        foreach ($explodePath as $item) {
            $routeItem = $this->setRouteItem($item, $data, $routeName, $controller, $routeAnnotation);
            $path     .= '/' . $routeItem;
        }

        return $path;
    }

    /**
     * if route path fragment is a variable data it is replaced by the given value passed in $data argument.
     *
     * If the argument is missing in $data, the method will throws an exception.
     *
     * @param string          $item
     * @param array           $data
     * @param string          $routeName
     * @param string          $controller
     * @param RouteAnnotation $route
     *
     * @return string
     *
     * @throws AppException
     */
    private function setRouteItem(
        string $item,
        array $data,
        string $routeName,
        string $controller,
        RouteAnnotation $route
    ): string {
        if ($this->routeParser->isVariableRoutePathItem($item)) {
            $var = $this->routeParser->getVarName($item);
            $this->utils->isDataItemValid($var, $data, $routeName, $controller, $route);

            return $data[$var];
        }

        return $item;
    }
}
