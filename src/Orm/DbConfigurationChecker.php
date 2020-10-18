<?php

namespace Framework3\Orm;

use Framework3\Config\ConfigBag;
use Framework3\Exception\AppException;

class DbConfigurationChecker
{
    /**
     * @var array
     */
    private array $dbConfig;

    /**
     * DbConfigurationChecker constructor.
     *
     * @param ConfigBag $ormConfig
     *
     * @throws AppException
     */
    public function __construct(ConfigBag $ormConfig)
    {
        $this->dbConfig = $ormConfig->get('DB', true);
    }

    /**
     * @param string $connection
     *
     * @throws AppException
     */
    public function check(string $connection): void
    {
        $this->checkConfig($connection);
        $this->checkMissingParameters($connection);
        $this->checkDbDriver($connection);
        $this->checkDbPort($connection);
    }

    /**
     * @param string $connection
     *
     * @throws AppException
     */
    private function checkConfig(string $connection): void
    {
        if (!array_key_exists($connection, $this->dbConfig)) {
            throw new AppException(
                AppException::TYPE_ORM,
                'Configuration is missing for connection',
                $connection
            );
        }
    }

    /**
     * @param string $connection
     *
     * @throws AppException
     */
    private function checkDbDriver(string $connection): void
    {
        if (
            !in_array(
                $this->dbConfig[$connection][DbConnector::CONNECTION][DbConnector::DRIVER],
                DbConnector::SUPPORTED_DRIVERS
            )
        ) {
            throw new AppException(
                AppException::TYPE_ORM,
                sprintf(
                    'Driver "%s" is not supported at this time',
                    $this->dbConfig[$connection][DbConnector::CONNECTION][DbConnector::DRIVER]
                ),
                sprintf('Connection: "%s"', $connection)
            );
        }
    }

    /**
     * @param string $connection
     *
     * @throws AppException
     */
    private function checkDbPort(string $connection): void
    {
        if ((int)$this->dbConfig[$connection][DbConnector::CONNECTION][DbConnector::PORT] === 0) {
            throw new AppException(
                AppException::TYPE_ORM,
                'Invalid PORT parameter',
                sprintf('Connection: "%s"', $connection)
            );
        }
    }

    /**
     * @param string $connection
     *
     * @throws AppException
     */
    private function checkMissingParameters(string $connection): void
    {
        $parameters = [
            DbConnector::DRIVER,
            DbConnector::HOST,
            DbConnector::PORT,
            DbConnector::DBNAME,
            DbConnector::USERNAME,
            DbConnector::PASSWORD
        ];

        foreach ($parameters as $parameter) {
            if (!array_key_exists($parameter, $this->dbConfig[$connection][DbConnector::CONNECTION])) {
                throw new AppException(
                    AppException::TYPE_ORM,
                    sprintf('%s parameter is missing in DB configuration file', $parameter),
                    sprintf('Connection: "%s"', $connection)
                );
            }
        }
    }
}
