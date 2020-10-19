<?php

namespace Climb\Templating\Twig;

use Climb\Routing\RouteBuilderInterface;
use Climb\Security\UserManagerInterface;
use Twig\Environment;
use Twig\TwigFunction;

class ExtensionManager
{
    /**
     * @var RouteBuilderInterface
     */
    private RouteBuilderInterface $routeBuilder;

    /**
     * @var UserManagerInterface
     */
    private UserManagerInterface $userManager;

    /**
     * @var Environment
     */
    private Environment $environment;

    public function __construct(RouteBuilderInterface $routeBuilder, UserManagerInterface $userManager)
    {
        $this->routeBuilder = $routeBuilder;
        $this->userManager  = $userManager;
    }

    public function setExtensions(Environment $environment, array $flashes)
    {
        $this->environment = $environment;

        $this->setRouteBuilderExtension();
        $this->setUserExtension();
        $this->setUserGrantedExtension();
        $this->setFlashesExtension($flashes);
    }

    /**
     * Add route builder `setRoutePath()` method.
     */
    private function setRouteBuilderExtension(): void
    {
        $routerExtension = new TwigFunction('getRoutePath', [$this->routeBuilder, 'getRoutePath']);
        $this->environment->addFunction($routerExtension);
    }

    /**
     * Add authenticated user or null
     */
    private function setUserExtension(): void
    {
        $this->environment->addGlobal('user', $this->userManager->getUser());
    }

    /**
     * Add user manager isGranted method
     */
    private function setUserGrantedExtension(): void
    {
        $userExtension = new TwigFunction('isGranted', [$this->userManager, 'isGranted']);
        $this->environment->addFunction($userExtension);
    }

    /**
     * Add retrieved flashes messages
     *
     * @param array $flashes
     */
    private function setFlashesExtension(array $flashes): void
    {
        $this->environment->addGlobal('flashes', $flashes);
    }
}
