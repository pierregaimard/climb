<?php

namespace Framework3\Security;

interface UserInterface
{
    /**
     * Should return the user UNIQUE identifier.
     *
     * This can be every user UNIQUE attribute like email, login, ...
     *
     * @example return $this->getEmail();
     *
     * @return string
     */
    public function getUsername();

    /**
     * Should returns user's hashed password.
     *
     * @return string
     */
    public function getPassword();

    /**
     * Should returns an array of user roles.
     *
     * @return array
     */
    public function getRoles();

    /**
     * Should check if user has role $role
     *
     * @param string $role
     *
     * @example return in_array($role, $user->getRoles());
     *
     * @return bool
     */
    public function isGranted(string $role): bool;
}
