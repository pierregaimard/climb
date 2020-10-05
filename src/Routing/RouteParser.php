<?php

/**
 * This service provides methods for the Router to parse a request path and compare it width a Route declaration.
 */

namespace Framework3\Routing;

use Framework3\Routing\Annotation\Route as RouteAnnotation;

class RouteParser
{
    /**
     * Checks if a given route path declaration contains variable data.
     *
     * @param string $path Given route path. e.g. `@Route(path="/admin/user/{id}")`
     *
     * @return bool
     */
    public function isVariableRoutePath(string $path): bool
    {
        return strpos($path, '{') !== false;
    }

    /**
     * Checks if a given fragment of a route path declaration is a variable element.
     *
     * e.g. Fragment `{id}` or `user` of Route path declaration `"/admin/user/{id}"`
     *
     * @param string $item Fragment of a request path.
     *
     * @return bool
     */
    public function isVariableRoutePathItem(string $item): bool
    {
        return substr($item, 0, 1) === '{';
    }

    /**
     * Returns variable name from a given Route path fragment.
     *
     * e.g. Fragment `{id}` will return "id"
     *
     * @param string $item Fragment of a request path.
     *
     * @return string
     */
    public function getVarName(string $item): string
    {
        return substr($item, 1, -1);
    }

    /**
     * Checks if a given request path matches width a Route annotation declaration.
     *
     * @param RouteAnnotation   $routeAnnotation    Route Annotation object
     * @param string            $requestPath        Request path.
     *
     * @return bool
     */
    public function isRouteMatch(RouteAnnotation $routeAnnotation, string $requestPath): bool
    {
        $routePathExplode   = explode('/', $routeAnnotation->getPath());
        $requestPathExplode = explode('/', $requestPath);
        $count              = 0;

        foreach ($routePathExplode as &$item) {
            $isVariableRouteItem = $this->isVariableRoutePathItem($item);

            /*
             * foreach route/requestPath fragment,
             * if the route path doesn't match width requestPath: returns false.
             */
            if ($requestPathExplode[$count] !== $item && !$isVariableRouteItem) {
                return false;
            }

            $count++;

            /*
             * for variable route path fragment, if regex have been declared in route annotation,
             * replaces segment by route annotation pattern, else replaces segment by generic .* pattern.
             */
            if ($isVariableRouteItem) {
                $item = $this->setPattern($item, $routeAnnotation);
            }
        }
        // test if global pattern matches width requestPath.
        $pattern = '#^' . implode('/', $routePathExplode) . '$#';

        return preg_match($pattern, $requestPath) !== 0;
    }

    /**
     * returns an associative array from requestPath data and route path var(s) declaration.
     *
     *  e.g.
     *      for `@Route(path="/user/view/{id}")`
     *      width requestPath `/user/view/1254`.
     *      the method will return the array `["id" => 1254]`
     *
     * @param RouteAnnotation $routeAnnotation Route annotation object
     * @param string          $requestPath     Request path
     *
     * @return array
     */
    public function getRouteData(RouteAnnotation $routeAnnotation, string $requestPath): array
    {
        $data               = [];
        $routePathExplode   = explode('/', $routeAnnotation->getPath());
        $requestPathExplode = explode('/', $requestPath);
        $count              = 0;

        foreach ($routePathExplode as $item) {
            if ($this->isVariableRoutePathItem($item)) {
                $data[] = $requestPathExplode[$count];
            }
            $count++;
        }

        return $data;
    }

    /**
     * @param string          $item
     * @param RouteAnnotation $route
     *
     * @return string
     */
    private function setPattern(string $item, RouteAnnotation $route): string
    {
        /*
         * for variable route path fragment, if regex have been declared in route annotation,
         * replaces segment by route annotation pattern, else replaces segment by generic .* pattern.
         */
        if ($route->getRegex() !== null) {
            $var = $this->getVarName($item);

            if (array_key_exists($var, $route->getRegex())) {
                return $route->getRegex()[$var];
            }
        }
        
        return '.*';
    }
}
