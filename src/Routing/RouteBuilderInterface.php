<?php

namespace Climb\Routing;

interface RouteBuilderInterface
{
    /**
     * Should Return a route path from route name and an array of variable data if present.
     *
     * This Method must return a string of route path,
     * or throws an exception if no route declaration matches width the given name.
     *
     * @param string     $routeName Name given in route declaration.
     * @param array|null $data      Array of data for the route. (if route path contains variable data)
     *
     * @return string
     */
    public function getRoutePath(string $routeName, array $data = null);
}
