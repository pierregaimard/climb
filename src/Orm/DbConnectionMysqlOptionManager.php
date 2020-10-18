<?php

namespace Framework3\Orm;

use PDO;

class DbConnectionMysqlOptionManager
{
    /**
     * @param array $configOptions
     *
     * @return array
     */
    public function getOptions(array $configOptions)
    {
        $pdoOptions = [];

        $this->setCharsetOption($configOptions, $pdoOptions);

        return $pdoOptions;
    }

    /**
     * @param array $configOptions
     * @param array $pdoOptions
     */
    private function setCharsetOption(array $configOptions, array &$pdoOptions): void
    {
        // CHARSET option
        if (array_key_exists("CHARSET", $configOptions)) {
            $pdoOptions[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES {$configOptions["CHARSET"]}";
        }
    }
}
