<?php

namespace Climb\Controller;

use Climb\Config\ConfigBag;
use Climb\Http\RedirectResponse;
use Climb\Http\Request;
use Climb\Orm\Orm;
use Climb\Routing\RouteBuilderInterface;
use Climb\Service\Container;
use Twig\Environment;
use Climb\Bag\Bag;
use Climb\Exception\AppException;
use Climb\Security\UserInterface;

class AbstractController
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var Container
     */
    protected Container $container;

    /**
     * @var Environment
     */
    private Environment $environment;

    /**
     * @var RouteBuilderInterface
     */
    private RouteBuilderInterface $routerBuilder;

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @param Container $container
     */
    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    /**
     * @param Environment $environment
     */
    public function setEnvironment(Environment $environment): void
    {
        $this->environment = $environment;
    }

    /**
     * @param RouteBuilderInterface $routeBuilder
     */
    public function setRouteBuilder(RouteBuilderInterface $routeBuilder): void
    {
        $this->routerBuilder = $routeBuilder;
    }

    /**
     * @return Orm
     *
     * @throws AppException
     */
    public function getOrm(): Orm
    {
        return $this->container->get(Orm::class);
    }

    /**
     * $this->environment->render() shortcut for controller
     *
     * @param string        $path
     * @param array|null    $data
     *
     * @return string
     */
    protected function render(string $path, array $data = null): string
    {
        return ($data !== null) ? $this->environment->render($path, $data) : $this->environment->render($path);
    }

    /**
     * $this->router->setRoutePath() Shortcut for controller
     *
     * @param string $name
     * @param array|null $data
     *
     * @return string
     */
    protected function getRoutePath(string $name, ?array $data = null): string
    {
        return $this->routerBuilder->getRoutePath($name, $data);
    }

    /**
     * @param string $path
     * @param array|null $routeData
     * @param array|null $data
     *
     * @return RedirectResponse
     */
    protected function redirectToRoute(string $path, ?array $routeData = null, ?array $data = null): RedirectResponse
    {
        return new RedirectResponse($this->getRoutePath($path, $routeData), $data);
    }

    /**
     * @param string $path
     * @param bool $required
     *
     * @throws AppException
     *
     * @return ConfigBag|false
     */
    protected function getConfig(string $path, bool $required = false)
    {
        return $this->container->getConfig($path, $required);
    }

    /**
     * @return Request
     */
    protected function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Shortcut to retrieve current user from the session.
     *
     * @return UserInterface|null
     */
    protected function getUser()
    {
        return $this->request->getSession()->getUser();
    }

    /**
     * Shortcut to check user access.
     *
     * @param string $role
     *
     * @return bool
     */
    protected function isGranted(string $role)
    {
        if ($this->request->getSession()->hasUser()) {
            return $this->request->getSession()->getUser()->isGranted($role);
        }

        return false;
    }

    /**
     * @return Bag
     */
    protected function getFlashes()
    {
        return $this->request->getSession()->getFlashes();
    }

    /**
     * @return Bag
     */
    protected function getRequestData()
    {
        return $this->request->getSession()->getRequestData();
    }
}
