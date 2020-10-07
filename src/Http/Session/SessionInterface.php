<?php

namespace Framework3\Http\Session;

use Framework3\Bag\Bag;
use Framework3\Security\UserInterface;

/**
 * Interface SessionInterface
 *
 * @package Climb\Session
 */
interface SessionInterface
{
    /**
     * Used to transport flash messages
     *
     * Should be set when the response is sent and destroy when request have been retrieved
     *
     * @return Bag
     */
    public function getFlashes(): Bag;

    /**
     * Used to write Flashes data in the super global `$_SESSION`
     */
    public function setFlashes(): void;

    /**
     * Used for additional data transport.
     *
     * Should be set when the response is sent and destroy when request have been retrieved
     *
     * @return Bag
     */
    public function getRequestData(): Bag;

    /**
     * Used to write Request data data in the super global `$_SESSION`
     */
    public function setRequestData(): void;

    /**
     * Used to store final app. user session data.
     *
     * @param string $item
     *
     * @return mixed|false
     */
    public function get(string $item);

    /**
     * Should set an item value in App session
     *
     * @param string    $item
     * @param mixed     $value
     *
     * @return mixed
     */
    public function add(string $item, $value);

    /**
     * Should unset an item in App session
     *
     * Should Returns true if value exists, or false if not.
     *
     * @param string $item
     *
     * @return bool
     */
    public function remove(string $item): bool;

    /**
     * Should verify if an App session item is set.
     *
     * @param string $item
     *
     * @return bool
     */
    public function has(string $item): bool;

    /**
     * Should return global App session array.
     *
     * @return array|null
     */
    public function getAll(): ?array;

    /**
     * Should set global App session data.
     *
     * Must be an array.
     *
     * @param array $data
     */
    public function setAll(array $data): void;

    /**
     * Should unset all App session data.
     */
    public function removeAll(): void;

    /**
     * Used to store current user
     *
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface;

    /**
     * Should set the curent user object
     *
     * @param UserInterface $user
     *
     * @return mixed
     */
    public function setUser(UserInterface $user);

    /**
     * Should unset user if exists.
     *
     * If user exists, the method returns true, otherwise false.
     *
     * @return bool
     */
    public function unsetUser(): bool;

    /**
     * Should verify if user session is set and is instance of UserInterface.
     *
     * @return bool
     */
    public function hasUser(): bool;
}
