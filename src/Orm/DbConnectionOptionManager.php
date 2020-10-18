<?php

namespace Framework3\Orm;

use PDO;
use Framework3\Config\ConfigBag;
use Framework3\Exception\AppException;

class DbConnectionOptionManager
{
    /**
     * @var DbConnectionMysqlOptionManager
     */
    private DbConnectionMysqlOptionManager $mysqlOptionManager;

    /**
     * @var array
     */
    private array $dbConfig;

    /**
     * DbConfigurationChecker constructor.
     *
     * @param DbConnectionMysqlOptionManager $mysqlOptionManager
     * @param ConfigBag                      $ormConfig
     *
     * @throws AppException
     */
    public function __construct(DbConnectionMysqlOptionManager $mysqlOptionManager, ConfigBag $ormConfig)
    {
        $this->mysqlOptionManager = $mysqlOptionManager;
        $this->dbConfig           = $ormConfig->get('DB', true);
    }

    public function getOptions(string $connection): ?array
    {
        $pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

        if (!$this->hasOptions($connection)) {
            return $pdoOptions;
        }

        $configOptions = $this->getConfigOptions($connection);

        switch ($this->dbConfig[$connection][DbConnector::CONNECTION][DbConnector::DRIVER]) {
            case "mysql":
                $pdoOptions = $this->mysqlOptionManager->getOptions($configOptions);
                break;
            default:
                break;
        }

        return isset($pdoOptions) ? $pdoOptions : null;
    }

    /**
     * @param string $connection
     *
     * @return array
     */
    private function getConfigOptions(string $connection): array
    {
        return $this->dbConfig[$connection][DbConnector::CONNECTION][DbConnector::OPTIONS];
    }

    /**
     * @param string $connection
     *
     * @return bool
     */
    private function hasOptions(string $connection): bool
    {
        return array_key_exists(DbConnector::OPTIONS, $this->dbConfig[$connection][DbConnector::CONNECTION]);
    }
}
