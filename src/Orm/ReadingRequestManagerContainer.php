<?php

namespace Framework3\Orm;

use Framework3\Exception\AppException;

class ReadingRequestManagerContainer
{
    /**
     * @var DbConnectionManager
     */
    private DbConnectionManager $connectionManager;

    /**
     * @var EntityMappingManager
     */
    private EntityMappingManager $mappingManager;

    /**
     * @var EntityDataManager
     */
    private EntityDataManager $dataManager;

    /**
     * @var SelectRequestBuilder
     */
    private SelectRequestBuilder $selectBuilder;

    /**
     * @var SelectEntityRequestManager[]
     */
    private array $eSelectManager = [];

    /**
     * @var SelectCollectionRequestManager[]
     */
    private array $cSelectManager = [];
    
    public function __construct(
        DbConnectionManager $connectionManager,
        EntityMappingManager $mappingManager,
        EntityDataManager $dataManager,
        SelectRequestBuilder $selectBuilder
    ) {
        $this->connectionManager = $connectionManager;
        $this->mappingManager = $mappingManager;
        $this->dataManager = $dataManager;
        $this->selectBuilder = $selectBuilder;
    }

    /**
     * @param string $connection
     *
     * @return SelectEntityRequestManager
     *
     * @throws AppException
     */
    public function getSelectEntityManager(string $connection): SelectEntityRequestManager
    {
        if (array_key_exists($connection, $this->eSelectManager)) {
            return $this->eSelectManager[$connection];
        }

        $this->setSelectEntityManager($connection);

        return $this->eSelectManager[$connection];
    }

    /**
     * @param string $connection
     *
     * @return SelectCollectionRequestManager
     *
     * @throws AppException
     */
    public function getSelectCollectionManager(string $connection): SelectCollectionRequestManager
    {
        if (array_key_exists($connection, $this->cSelectManager)) {
            return $this->cSelectManager[$connection];
        }

        $this->setSelectCollectionManager($connection);

        return $this->cSelectManager[$connection];
    }

    /**
     * @param string $connection
     *
     * @throws AppException
     */
    private function setSelectEntityManager(string $connection): void
    {
        $this->eSelectManager[$connection] = new SelectEntityRequestManager(
            $this->connectionManager->getPdo($connection),
            $this->mappingManager,
            $this->dataManager,
            $this->selectBuilder
        );
    }

    /**
     * @param string $connection
     *
     * @throws AppException
     */
    private function setSelectCollectionManager(string $connection): void
    {
        $this->cSelectManager[$connection] = new SelectCollectionRequestManager(
            $this->connectionManager->getPdo($connection),
            $this->mappingManager,
            $this->dataManager,
            $this->selectBuilder
        );
    }
}
