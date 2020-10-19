<?php

namespace Climb\Routing;

class Route implements RouteInterface
{
    /**
     * Controller class name for this route.
     *
     * @var string
     */
    private string $controller;

    /**
     * Controller Method name for this route.
     *
     * @var string
     */
    private string $method;

    /**
     * Variable data retrieved from the route path.
     *
     * @example `@Route(path="/user/view/{id}")` width request path `"/user/view/125"` will returns `["id" => 125]`
     *
     * @var array|null
     */
    private ?array $data;

    public function __construct(string $controller, string $method, array $data = null)
    {
        $this->controller = $controller;
        $this->method     = $method;
        $this->data       = $data;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }
}
