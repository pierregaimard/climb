<?php

namespace Framework3\Security;

use Framework3\Http\Session\SessionInterface;

class UserManager implements UserManagerInterface
{
    /**
     * @var UserPasswordManager
     */
    private UserPasswordManager $userPasswordManager;

    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * UserManager constructor.
     *
     * @param UserPasswordManager $userPasswordManager
     * @param SessionInterface $session
     */
    public function __construct(
        UserPasswordManager $userPasswordManager,
        SessionInterface $session
    ) {
        $this->userPasswordManager = $userPasswordManager;
        $this->session             = $session;
    }

    /**
     * @param UserInterface $user
     *
     * @return bool
     */
    public function setUser(UserInterface $user): bool
    {
        $this->session->setUser($user);

        return $this->session->hasUser();
    }

    /**
     * @return bool
     */
    public function unsetUser(): bool
    {
        return $this->session->unsetUser();
    }

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->session->getUser();
    }

    /**
     * @return bool
     */
    public function hasUser(): bool
    {
        return $this->session->hasUser();
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    public function isGranted(string $role): bool
    {
        if (!$this->hasUser()) {
            return false;
        }

        return  $this->getUser()->isGranted($role);
    }
}
