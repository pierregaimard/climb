<?php

namespace Climb\Routing;

use Climb\ClassFinder\FinderInterface;
use Climb\Config\ConfigBag;
use Climb\Exception\AppException;

class RouteControllerContainer
{
    /**
     * @var FinderInterface
     */
    private FinderInterface $appClassFinder;

    /**
     * Controller configuration.
     *
     * @var ConfigBag
     */
    private ConfigBag $controllerConfig;

    /**
     * @var array|null
     */
    private ?array $container = [];

    /**
     * @param FinderInterface $appClassFinder
     * @param ConfigBag       $controllerConfig
     */
    public function __construct(FinderInterface $appClassFinder, ConfigBag $controllerConfig)
    {
        $this->appClassFinder   = $appClassFinder;
        $this->controllerConfig = $controllerConfig;
    }

    /**
     * Retrieves and sets the list of controllers from given controller base namespace.
     *
     * (config file `lib/controller.json` : `BASE_NAMESPACE`)
     *
     * @return array|null
     *
     * @throws AppException
     */
    public function getControllersClasses(): ?array
    {
        if (!$this->container && !empty($this->container)) {
            return $this->container;
        }

        $this->container = $this->appClassFinder->getClassesList(
            $this->getControllerBaseNamespace(),
            true,
            $this->getControllerSuffix()
        );

        return $this->container;
    }

    /**
     * Returns `BASE_NAMESPACE` setting from `lib/controller` configuration file.
     *
     * This setting is used to scan and retrieves automatically the list of controllers
     * width `ClassFinder\Finder` service.
     *
     * @return string
     *
     * @throws AppException
     */
    private function getControllerBaseNamespace(): string
    {
        return $this->controllerConfig->get('BASE_NAMESPACE', true);
    }

    /**
     * Returns `SUFFIX` setting from `lib/controller` configuration file.
     *
     * This setting is used to retrieves only controllers services classes
     * that must use `SUFFIX` suffix.
     * e.g. `AdminController` where `Controller` is the suffix.
     *
     * @return string
     *
     * @throws AppException
     */
    private function getControllerSuffix(): string
    {
        return $this->controllerConfig->get('SUFFIX', true);
    }
}
