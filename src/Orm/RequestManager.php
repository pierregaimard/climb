<?php

namespace Climb\Orm;

use PDO;

class RequestManager
{
    /**
     * @var PDO
     */
    private PDO $pdo;

    /**
     * @var EntityMappingManager
     */
    private EntityMappingManager $mappingManager;

    /**
     * @var EntityDataManager
     */
    private EntityDataManager $dataManager;

    public function __construct(PDO $pdo, EntityMappingManager $mappingManager, EntityDataManager $dataManager)
    {
        $this->pdo            = $pdo;
        $this->mappingManager = $mappingManager;
        $this->dataManager    = $dataManager;
    }

    /**
     * @return PDO
     */
    protected function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @return EntityMappingManager
     */
    protected function getMappingManager(): EntityMappingManager
    {
        return $this->mappingManager;
    }

    /**
     * @return EntityDataManager
     */
    protected function getDataManager(): EntityDataManager
    {
        return $this->dataManager;
    }
}
