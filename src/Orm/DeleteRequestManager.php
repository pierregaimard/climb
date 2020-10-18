<?php

namespace Framework3\Orm;

use PDO;
use Framework3\Exception\AppException;

class DeleteRequestManager extends RequestManager
{
    /**
     * @var DeleteRequestBuilder
     */
    private DeleteRequestBuilder $builder;

    public function __construct(
        PDO $pdo,
        EntityMappingManager $mappingManager,
        EntityDataManager $dataManager,
        DeleteRequestBuilder $builder
    ) {
        parent::__construct($pdo, $mappingManager, $dataManager);
        $this->builder = $builder;
    }

    /**
     * @param object $entity
     *
     * @return bool
     *
     * @throws AppException
     */
    public function deleteOne(object $entity)
    {
        $mapping = $this->getMappingManager()->getEntityMapping(get_class($entity));
        $request = $this->builder->getDeleteRequest($mapping->getTableName());
        $delete  = $this->getPdo()->prepare($request);

        $delete->execute($this->getDataManager()->getPrimaryColumnData($mapping, $entity));

        return true;
    }

    /**
     * @param array $entities
     *
     * @return bool
     *
     * @throws AppException
     */
    public function deleteMany(array $entities)
    {
        $mapping = $this->getMappingManager()->getEntityMapping(get_class($entities[array_key_first($entities)]));
        $request = $this->builder->getDeleteRequest($mapping->getTableName());
        $delete  = $this->getPdo()->prepare($request);

        foreach ($entities as $entity) {
            $delete->execute($this->getDataManager()->getPrimaryColumnData($mapping, $entity));
        }

        return true;
    }

    /**
     * @param string $association
     * @param array  $search
     *
     * @return bool
     *
     * @throws AppException
     */
    public function deleteAssociations(string $association, array $search): bool
    {
        $request = $this->builder->getDeleteRequest($association, $search);
        $delete  = $this->getPdo()->prepare($request);
        $delete->execute($search);

        return true;
    }

    /**
     * @param string $class
     * @param array  $search
     *
     * @return bool
     *
     * @throws AppException
     */
    public function deleteBy(string $class, array $search): bool
    {
        $mapping = $this->getMappingManager()->getEntityMapping($class);
        $request = $this->builder->getDeleteRequest($mapping->getTableName(), $search);
        $delete  = $this->getPdo()->prepare($request);
        $delete->execute($search);

        return true;
    }
}
