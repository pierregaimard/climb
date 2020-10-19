<?php

namespace Climb\Routing;

use Climb\Exception\AppException;
use Climb\Routing\Annotation\Route as RouteAnnotation;

class RouteBuilderUtils
{
    /**
     * Checks if `$data` is an array.
     *
     * @param array  $data
     * @param string $routeName
     * @param string $controller
     *
     * @throws AppException
     */
    public function isDataArray(array $data, string $routeName, string $controller): void
    {
        if (!(array)$data) {
            throw new AppException(
                AppException::TYPE_ROUTER,
                "getRoutePath Exception.",
                sprintf(
                    'Route arguments are missing. Route: "%s", Controller: "%s"',
                    $routeName,
                    $controller
                )
            );
        }
    }

    /**
     * Checks if the argument is in $data array.
     *
     * @param string          $var
     * @param array           $data
     * @param string          $routeName
     * @param string          $controller
     * @param RouteAnnotation $route
     *
     * @throws AppException
     */
    public function isDataItemValid(
        string $var,
        array $data,
        string $routeName,
        string $controller,
        RouteAnnotation $route
    ): void {
        if (!array_key_exists($var, $data)) {
            throw new AppException(
                AppException::TYPE_ROUTER,
                "getRoutePath Exception.",
                sprintf(
                    'Route argument is missing. Route: "%s", Argument: "%s", Controller: "%s"',
                    $routeName,
                    $var,
                    $controller
                )
            );
        }

        if (empty($route->getRegex())) {
            return;
        }

        if (
            array_key_exists($var, $route->getRegex()) &&
            preg_match('#^' . $route->getRegex()[$var] . '$#', $data[$var]) === 0
        ) {
            throw new AppException(
                AppException::TYPE_ROUTER,
                "getRoutePath Exception.",
                sprintf(
                    'Route argument don\'t matches width regex. ' .
                    'Controller: "%s", Route: "%s", Argument: "%s", Regex: "%s"',
                    $controller,
                    $routeName,
                    $var,
                    $route->getRegex()[$var]
                )
            );
        }
    }
}
