<?php

namespace Framework3\Orm;

use Framework3\Exception\AppException;

class EntityManager
{
    /**
     * @var InsertRequestManager
     */
    private InsertRequestManager $insertManager;

    /**
     * @var UpdateRequestManager
     */
    private UpdateRequestManager $updateManager;

    /**
     * @var DeleteRequestManager
     */
    private DeleteRequestManager $deleteManager;

    /**
     * @var SelectEntityRequestManager $eSelectManager
     */
    private SelectEntityRequestManager $eSelectManager;

    /**
     * @var SelectCollectionRequestManager
     */
    private SelectCollectionRequestManager $cSelectManager;

    /**
     * @var EntityRepositoryUtils
     */
    private EntityRepositoryUtils $repositoryUtils;

    /**
     * @var EntityMappingUtils
     */
    private EntityMappingUtils $mappingUtils;

    /**
     * @param InsertRequestManager           $insertManager
     * @param UpdateRequestManager           $updateManager
     * @param DeleteRequestManager           $deleteManager
     * @param SelectEntityRequestManager     $eSelectManager
     * @param SelectCollectionRequestManager $cSelectManager
     * @param EntityRepositoryUtils          $repositoryUtils
     * @param EntityMappingUtils             $mappingUtils
     */
    public function __construct(
        InsertRequestManager $insertManager,
        UpdateRequestManager $updateManager,
        DeleteRequestManager $deleteManager,
        SelectEntityRequestManager $eSelectManager,
        SelectCollectionRequestManager $cSelectManager,
        EntityRepositoryUtils $repositoryUtils,
        EntityMappingUtils $mappingUtils
    ) {
        $this->insertManager   = $insertManager;
        $this->updateManager   = $updateManager;
        $this->deleteManager   = $deleteManager;
        $this->eSelectManager  = $eSelectManager;
        $this->cSelectManager  = $cSelectManager;
        $this->repositoryUtils = $repositoryUtils;
        $this->mappingUtils    = $mappingUtils;

        $this->updateManager->setDeleteManager($deleteManager);
        $this->updateManager->setInsertManager($insertManager);
    }

    /**
     * @param string $entity
     *
     * @return EntityRepository
     *
     * @throws AppException
     */
    public function getRepository(string $entity): EntityRepository
    {
        $repositoryClass = $this->repositoryUtils->getRepositoryClass($entity);

        return new $repositoryClass($entity, $this->eSelectManager, $this->cSelectManager, $this->mappingUtils);
    }

    /**
     * @param $entity
     *
     * @return int Returns the last insert Id
     *
     * @throws AppException
     */
    public function insertOne($entity): int
    {
        return $this->insertManager->insertOne($entity);
    }

    /**
     * @param array $entities MUST be same entity class
     *
     * @return array Array of inserted id
     *
     * @throws AppException
     */
    public function insertMany(array $entities): array
    {
        return $this->insertManager->insertMany($entities);
    }

    /**
     * @param $entity
     *
     * @return bool Result of the update
     *
     * @throws AppException
     */
    public function updateOne($entity): bool
    {
        return $this->updateManager->updateOne($entity);
    }

    /**
     * @param array $entities
     *
     * @return bool Result of the updates
     *
     * @throws AppException
     */
    public function updateMany(array $entities): bool
    {
        return $this->updateManager->updateMany($entities);
    }

    /**
     * @param $entity
     *
     * @return bool Result of the delete
     *
     * @throws AppException
     */
    public function deleteOne($entity): bool
    {
        return $this->deleteManager->deleteOne($entity);
    }

    /**
     * @param array $entities
     *
     * @return bool Result of the deletes
     *
     * @throws AppException
     */
    public function deleteMany(array $entities): bool
    {
        return $this->deleteManager->deleteMany($entities);
    }
}
