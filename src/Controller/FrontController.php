<?php

namespace Framework3\Controller;

use Framework3\Config\ConfigBag;
use Framework3\Exception\AppException;
use Framework3\Http\Response;
use Framework3\Kernel;
use Framework3\Routing\Route;
use Framework3\Security\SecurityChecker;
use Framework3\Routing\GlobalRouteManager;

class FrontController
{
    /**
     * @var Kernel
     */
    private Kernel $kernel;

    /**
     * @var SecurityChecker
     */
    private SecurityChecker $securityChecker;

    /**
     * @var GlobalRouteManager
     */
    private GlobalRouteManager $globalRouteManager;

    /**
     * @var ConfigBag|null
     */
    private ?ConfigBag $routingConfig;

    /**
     * FrontController constructor.
     *
     * @throws AppException
     */
    public function __construct()
    {
        $this->kernel = new Kernel();
        $this->kernel->load();

        $this->securityChecker    = $this->kernel->getContainer()->get(SecurityChecker::class);
        $this->globalRouteManager = $this->kernel->getContainer()->get(GlobalRouteManager::class);
        $this->routingConfig      = $this->kernel->getContainer()->getConfig('lib/routing');
    }

    /**
     * @param string    $controller
     *
     * @return AbstractController
     *
     * @throws AppException
     */
    private function getController(string $controller): AbstractController
    {
        $controller = $this->kernel->getContainer()->get($controller);
        $controller->setRequest($this->kernel->getRequest());
        $controller->setContainer($this->kernel->getContainer());
        $controller->setEnvironment($this->kernel->getEnvironment());
        $controller->setRouteBuilder($this->kernel->getRouteBuilder());

        return $controller;
    }

    /**
     * Returns a valid Response object from $request.
     *
     * This method retrieves the route info from request path,
     * Instantiates the controller and returns a response from Route method.
     * If no route found, a 404 Not found response will be return:
     *      - if a custom controller have been set, response is retrieved from it
     *      - if no custom controller have been set,
     *        response is retrieved from default controller: Framework3\Controller\Status\StatusController
     *
     * @return Response
     *
     * @throws AppException
     */
    public function getResponse(): Response
    {
        $request = $this->kernel->getRequest();
        $route = $this->kernel->getRouter()->getRoute($request->getPath());

        // 404 Not found
        if ($route === false) {
            $route = $this->globalRouteManager->getRoute(404, $request->getPath());
        }

        // Security check
        if (!$this->securityChecker->isUserGranted($route)) {
            $route = $this->getNoAccessRoute($request->getPath());
        }

        // set controller
        $controller = $this->getController($route->getController());
        $method = $route->getMethod();
        $data = $route->getData();

        // get response from controller
        $response = (is_array($data)) ? $controller->$method(...$data) : $controller->$method();

        $this->checkBadControllerResponse($route, $response);

        return $response;
    }

    /**
     * @param string $path
     *
     * @return Route
     */
    private function getNoAccessRoute(string $path): Route
    {
        if ($this->kernel->getUserManager()->hasUser()) {
            return $this->globalRouteManager->getRoute(403, $path);
        }

        return $this->globalRouteManager->getRoute(401, $path);
    }

    /**
     * throws Exception if controller do not returns a valid Response object.
     *
     * @param Route $route
     * @param mixed $response
     *
     * @throws AppException
     */
    private function checkBadControllerResponse(Route $route, $response): void
    {
        if (!$response instanceof Response) {
            throw new AppException(
                AppException::TYPE_CONTROLLER,
                'The Controller did not return a valid response object.',
                sprintf(
                    'Controller: "%s", Method: "%s"',
                    $route->getController(),
                    $route->getMethod()
                )
            );
        }
    }
}
