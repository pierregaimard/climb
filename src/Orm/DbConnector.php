<?php

namespace Climb\Orm;

use Climb\Config\ConfigBag;
use Climb\Exception\AppException;
use PDO;

class DbConnector
{
    public const CONNECTION = 'CONNECTION';
    public const DRIVER     = 'DRIVER';
    public const HOST       = 'HOST';
    public const PORT       = 'PORT';
    public const DBNAME     = 'DBNAME';
    public const USERNAME   = 'USERNAME';
    public const PASSWORD   = 'PASSWORD';
    public const OPTIONS    = 'OPTIONS';
    public const CHARSET    = 'CHARSET';

    public const SUPPORTED_DRIVERS = [
        'mysql'
    ];

    /**
     * @var DbConfigurationChecker
     */
    private DbConfigurationChecker $configurationChecker;

    /**
     * @var DbConnectionOptionManager
     */
    private DbConnectionOptionManager $optionManager;

    /**
     * @var array
     */
    private array $dbConfig;

    /**
     * @param DbConfigurationChecker    $configurationChecker
     * @param DbConnectionOptionManager $optionManager
     * @param ConfigBag                 $ormConfig
     *
     * @throws AppException
     */
    public function __construct(
        DbConfigurationChecker $configurationChecker,
        DbConnectionOptionManager $optionManager,
        ConfigBag $ormConfig
    ) {
        $this->configurationChecker = $configurationChecker;
        $this->optionManager        = $optionManager;
        $this->dbConfig             = $ormConfig->get('DB', true);
    }

    /**
     * @param $connection
     *
     * @return PDO
     *
     * @throws AppException
     */
    public function getPdo($connection): PDO
    {
        $this->configurationChecker->check($connection);

        $dsn         = $this->getDSN($connection);
        $credentials = $this->getCredentials($connection);
        $options     = $this->optionManager->getOptions($connection);

        if ($options !== null) {
            return new PDO($dsn, $credentials[self::USERNAME], $credentials[self::PASSWORD], $options);
        }

        return new PDO($dsn, $credentials[self::USERNAME], $credentials[self::PASSWORD]);
    }

    /**
     * @param string $connection
     *
     * @return string|null
     */
    private function getDSN(string $connection): ?string
    {
        $config = $this->dbConfig[$connection][self::CONNECTION];

        $driver = $config['DRIVER'];
        $host   = $config['HOST'];
        $port   = $config['PORT'];
        $dbname = $config['DBNAME'];

        switch ($driver) {
            case 'mysql':
                return 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname;
            default:
                return null;
        }
    }

    /**
     * @param string $connection
     *
     * @return array
     */
    private function getCredentials(string $connection): array
    {
        return [
            self::USERNAME => $this->dbConfig[$connection][self::CONNECTION][self::USERNAME],
            self::PASSWORD => $this->dbConfig[$connection][self::CONNECTION][self::PASSWORD],
        ];
    }
}
