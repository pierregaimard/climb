<?php

namespace Climb\Routing;

use Climb\Config\ConfigBag;
use Climb\Exception\AppException;

class GlobalRouteManager
{
    /**
     * Key used in configuration file to declare status pages.
     */
    public const STATUS_PAGES_KEY = 'STATUS_PAGES';

    /**
     * Key used in configuration file to declare 404 Not Found Controller class.
     */
    public const STATUS_PAGE_CONTROLLER_KEY = 'controller';

    /**
     * Key used in configuration file to declare 404 Not Found Controller Method.
     */
    public const STATUS_PAGE_METHOD_KEY = 'method';

    /**
     * @var array
     */
    private array $routingConfig;

    /**
     * @param ConfigBag $routingConfig
     *
     * @throws AppException
     */
    public function __construct(ConfigBag $routingConfig)
    {
        $this->routingConfig = $routingConfig->get(self::STATUS_PAGES_KEY, true);
    }

    /**
     * @param int    $statusCode
     * @param string $path
     *
     * @return Route
     */
    public function getRoute(int $statusCode, string $path): Route
    {
        return new Route(
            $this->routingConfig[$statusCode][self::STATUS_PAGE_CONTROLLER_KEY],
            $this->routingConfig[$statusCode][self::STATUS_PAGE_METHOD_KEY],
            [$path]
        );
    }
}
