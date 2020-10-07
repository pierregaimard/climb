<?php

namespace Framework3\Http\Session;

use Framework3\Bag\Bag;
use Framework3\Security\UserInterface;

class Session implements SessionInterface
{
    /**
     * This session key is used to store flashes messages
     */
    public const FLASHES = 'FLASHES';

    /**
     * This session key is used to store request data.
     *
     * Stored informations:
     *  - RedirectResponse Data
     *  - request & prevent response data.
     *      This is used by front controller to keep user request and last response route.
     *      if response status different from 200 OK to be able to redirect the user
     *      after displaying error message.
     */
    public const REQUEST_DATA = 'DATA';

    /**
     * This session key is used for Final App session storage
     */
    public const APP = 'APP';

    /**
     * This session key is used to store curent user.
     */
    public const USER = 'USER';

    /**
     * @var Bag
     */
    private Bag $requestData;

    /**
     * @var Bag
     */
    private Bag $flashes;

    public function __construct()
    {
        $this->flashes     = new Bag();
        $this->requestData = new Bag();
    }

    public function initialize(): void
    {
        $session = $this->getFromGlobal();

        if (array_key_exists(self::FLASHES, $session) && $session[self::FLASHES] !== null) {
            $this->flashes->setAll($session[self::FLASHES]);
        }

        if (array_key_exists(self::REQUEST_DATA, $session) && $session[self::REQUEST_DATA] !== null) {
            $this->requestData->setAll($session[self::REQUEST_DATA]);
        }
    }

    /**
     * @return Bag
     */
    public function getFlashes(): Bag
    {
        return $this->flashes;
    }

    public function setFlashes(): void
    {
        $this->write(self::FLASHES, $this->getFlashes()->getAll());
    }

    /**
     * @return Bag
     */
    public function getRequestData(): Bag
    {
        return $this->requestData;
    }

    public function setRequestData(): void
    {
        $this->write(self::REQUEST_DATA, $this->getRequestData()->getAll());
    }
    
    /**
     * @param string $item
     *
     * @return mixed|false
     */
    public function get(string $item)
    {
        return $this->read(self::APP, $item);
    }

    /**
     * @param string $item
     *
     * @param $value
     */
    public function add(string $item, $value): void
    {
        $data        = $this->read(self::APP);
        $data[$item] = $value;

        $this->write(self::APP, $data);
    }

    /**
     * @param string $item
     *
     * @return bool
     */
    public function remove(string $item): bool
    {
        $data = $this->read(self::APP);
        if (array_key_exists($item, $data)) {
            unset($data[$item]);
            $this->write(self::APP, $data);

            return true;
        }

        return false;
    }

    /**
     * @param string $item
     *
     * @return bool
     */
    public function has(string $item): bool
    {
        return $this->read(self::APP, $item) != false;
    }

    /**
     * @return array|null
     */
    public function getAll(): ?array
    {
        return $this->read(self::APP);
    }

    /**
     * @param array $data
     */
    public function setAll(array $data): void
    {
        $this->write(self::APP, $data);
    }

    public function removeAll(): void
    {
        $this->write(self::APP, []);
    }

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->read(self::USER);
    }

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user): void
    {
        $this->write(self::USER, $user);
    }

    /**
     * @return bool
     */
    public function unsetUser(): bool
    {
        if ($this->hasUser()) {
            $this->write(self::USER, null);
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasUser(): bool
    {
        return $this->read(self::USER) instanceof UserInterface;
    }

    /**
     * @return array
     */
    private function getFromGlobal(): array
    {
        $session = $this->read();
        $this->write(Session::FLASHES, null);
        $this->write(Session::REQUEST_DATA, null);

        return $session;
    }

    /**
     * @param string $container
     * @param mixed  $data
     */
    private function write(string $container, $data): void
    {
        $_SESSION[$container] = $data;
    }

    /**
     * @param string|null $container
     * @param string|null $key
     *
     * @return mixed
     */
    private function read(string $container = null, string $key = null)
    {
        $session = $_SESSION;
        if (!$container) {
            return $session;
        }

        if (!$key) {
            return (array_key_exists($container, $session)) ? $session[$container] : false;
        }

        if (array_key_exists($container, $session) && array_key_exists($key, $session[$container])) {
            return $session[$container][$key];
        }

        return false;
    }
}
