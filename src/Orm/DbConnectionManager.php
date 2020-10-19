<?php

namespace Climb\Orm;

use PDO;
use Climb\Exception\AppException;

class DbConnectionManager
{
    /**
     * @var DbConnector
     */
    private DbConnector $connector;

    public function __construct(DbConnector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * @var PDO[]
     */
    private array $connections = [];

    /**
     * @param string $connection
     *
     * @return PDO
     *
     * @throws AppException
     */
    public function getPdo(string $connection): PDO
    {
        if (!$this->has($connection)) {
            $this->set($connection);
        }

        return $this->connections[$connection];
    }

    /**
     * @param string $connection
     *
     * @throws AppException
     */
    private function set(string $connection): void
    {
        $this->connections[$connection] = $this->connector->getPdo($connection);
    }

    /**
     * @param string $connection
     *
     * @return bool
     */
    private function has(string $connection): bool
    {
        return array_key_exists($connection, $this->connections);
    }
}
