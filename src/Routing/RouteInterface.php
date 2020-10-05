<?php

namespace Framework3\Routing;

interface RouteInterface
{
    /**
     * Must returns a fully qualified namespaced class name of the controller.
     *
     * @return string
     */
    public function getController();

    /**
     * Must returns the route method name of the controller.
     *
     * @return string
     */
    public function getMethod();

    /**
     * Should return an array of parsed data from variable route path.
     *
     * where the key is the variable data name and the value is the parsed value from request path.
     *
     * e.g.
     *      - route path: `"/admin/user/{id}"` where `{id}` is a variable data.
     *      - request path: `"/admin/user/254"`
     *      => should returns `['id' => 254]`
     *
     * @return array|null
     */
    public function getData();
}
