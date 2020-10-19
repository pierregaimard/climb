<?php

namespace Climb\Orm;

use Climb\Exception\AppException;

class WritingRequestManagerContainer
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
     * @var InsertRequestBuilder
     */
    private InsertRequestBuilder $insertBuilder;

    /**
     * @var UpdateRequestBuilder
     */
    private UpdateRequestBuilder $updateBuilder;

    /**
     * @var DeleteRequestBuilder
     */
    private DeleteRequestBuilder $deleteBuilder;

    public function __construct(
        DbConnectionManager $connectionManager,
        EntityMappingManager $mappingManager,
        EntityDataManager $dataManager,
        InsertRequestBuilder $insertBuilder,
        UpdateRequestBuilder $updateBuilder,
        DeleteRequestBuilder $deleteBuilder
    ) {
        $this->connectionManager = $connectionManager;
        $this->mappingManager    = $mappingManager;
        $this->dataManager       = $dataManager;
        $this->insertBuilder     = $insertBuilder;
        $this->updateBuilder     = $updateBuilder;
        $this->deleteBuilder     = $deleteBuilder;
    }

    /**
     * @param string $connection
     *
     * @return InsertRequestManager
     *
     * @throws AppException
     */
    public function getInsertManager(string $connection): InsertRequestManager
    {
        return new InsertRequestManager(
            $this->connectionManager->getPdo($connection),
            $this->mappingManager,
            $this->dataManager,
            $this->insertBuilder
        );
    }

    /**
     * @param string $connection
     *
     * @return UpdateRequestManager
     *
     * @throws AppException
     */
    public function getUpdateManager(string $connection): UpdateRequestManager
    {
        return new UpdateRequestManager(
            $this->connectionManager->getPdo($connection),
            $this->mappingManager,
            $this->dataManager,
            $this->updateBuilder
        );
    }

    /**
     * @param string $connection
     *
     * @return DeleteRequestManager
     *
     * @throws AppException
     */
    public function getDeleteManager(string $connection): DeleteRequestManager
    {
        return new DeleteRequestManager(
            $this->connectionManager->getPdo($connection),
            $this->mappingManager,
            $this->dataManager,
            $this->deleteBuilder
        );
    }
}
