<?php

namespace Framework3;

use Framework3\Http\Request;
use Framework3\Http\RequestManager;
use Framework3\Routing\RouteBuilder;
use Framework3\Routing\RouteBuilderInterface;
use Framework3\Routing\Router;
use Framework3\Routing\RouterInterface;
use Framework3\Security\UserManager;
use Framework3\Security\UserManagerInterface;
use Framework3\Service\Container;
use Framework3\Templating\Twig\TemplatingManager;
use Twig\Environment;

class Kernel
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var Container
     */
    private Container $container;

    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * @var RouteBuilderInterface
     */
    private RouteBuilderInterface $routeBuilder;

    /**
     * @var UserManagerInterface
     */
    private UserManagerInterface $userManager;

    /**
     * Twig environment
     *
     * @var TemplatingManager
     */
    private TemplatingManager $templatingManager;

    /**
     * @throws Exception\AppException
     */
    public function load()
    {
        $this->container         = new Container();
        $this->router            = $this->container->get(Router::class);
        $this->routeBuilder      = $this->container->get(RouteBuilder::class);
        $this->userManager       = $this->container->get(UserManager::class);
        $this->templatingManager = $this->container->get(TemplatingManager::class);
        $requestManager          = $this->container->get(RequestManager::class);
        $this->request           = $requestManager->getFromGlobals();
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * @return RouteBuilderInterface
     */
    public function getRouteBuilder(): RouteBuilderInterface
    {
        return $this->routeBuilder;
    }

    /**
     * @return Environment
     * @throws Exception\AppException
     */
    public function getEnvironment(): Environment
    {
        return $this->templatingManager->getEnvironment(
            $this->request->getSession()->getFlashes()->getAll()
        );
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager(): UserManagerInterface
    {
        return $this->userManager;
    }
}
