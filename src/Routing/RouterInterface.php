<?php

namespace Framework3\Routing;

interface RouterInterface
{
    /**
     * Should returns a `RouteInterface` object or false if requestPath doesn't matches any route.
     *
     * @param string $requestPath
     *
     * @return RouteInterface|false
     */
    public function getRoute(string $requestPath);
}
