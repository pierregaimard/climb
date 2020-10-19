<?php

namespace Climb\Security;

interface UserManagerInterface
{
    /**
     * Should set the user session using the key defined in Http\Session class.
     *
     * e.g. $_SESSION[Session::SESSION_USER] = new User();
     * Should return true if user session is set, false if a problem occurred.
     *
     * @param UserInterface $user
     *
     * @return bool
     */
    public function setUser(UserInterface $user): bool;

    /**
     * Should unset user session.
     *
     * This method should return true if user session have been destroyed,
     * or false if a problem occurred.
     *
     * @return bool
     */
    public function unsetUser(): bool;

    /**
     * Should return an instance of UserInterface of the current user.
     *
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface;

    /**
     * Should check if a user is set in the session
     *
     * @return bool
     */
    public function hasUser(): bool;

    /**
     * Should returns if current user is granted of $role.
     *
     * if no user set, the method should return false.
     *
     * @param string $role
     *
     * @return bool
     */
    public function isGranted(string $role);
}
