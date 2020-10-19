<?php

namespace Climb\Security;

use Climb\Annotation\ReaderManagerInterface;
use Climb\Routing\Route;
use Climb\Security\Annotation\Security;

class SecurityChecker
{
    /**
     * @var UserManagerInterface
     */
    private UserManagerInterface $userManager;

    /**
     * @var ReaderManagerInterface
     */
    private ReaderManagerInterface $readerManager;

    public function __construct(
        UserManagerInterface $userManager,
        ReaderManagerInterface $readerManager
    ) {
        $this->userManager   = $userManager;
        $this->readerManager = $readerManager;
    }

    /**
     * Checks if user role allow user for this route.
     *
     * This method uses Security annotation declared in controller method.
     * If no annotation is found, the user is granted.
     * If annotation is found, this method checks if user is granted.
     *
     * @param Route $route
     *
     * @return bool
     */
    public function isUserGranted(Route $route): bool
    {
        $reader = $this->readerManager->getReader($route->getController());

        $security = $reader->getMethodAnnotation(
            $route->getMethod(),
            Security::TAG,
            true
        );

        if ($security === null) {
            return true;
        }

        if (!$this->userManager->hasUser()) {
            return false;
        }

        $vote = false;

        foreach ($security->getRoles() as $role) {
            if ($this->userManager->getUser()->isGranted($role)) {
                $vote = true;
            }
        }

        return $vote;
    }
}
