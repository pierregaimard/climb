<?php

namespace Framework3\Orm;

use Framework3\Orm\Annotation\Relation;
use PDO;
use PDOStatement;
use Framework3\Exception\AppException;

class UpdateRequestManager extends RequestManager
{
    /**
     * @var UpdateRequestBuilder
     */
    private UpdateRequestBuilder $builder;

    /**
     * @var InsertRequestManager
     */
    private InsertRequestManager $insertManager;

    /**
     * @var DeleteRequestManager
     */
    private DeleteRequestManager $deleteManager;

    public function __construct(
        PDO $pdo,
        EntityMappingManager $mappingManager,
        EntityDataManager $dataManager,
        UpdateRequestBuilder $builder
    ) {
        parent::__construct($pdo, $mappingManager, $dataManager);
        $this->builder = $builder;
    }

    /**
     * @param DeleteRequestManager $deleteManager
     */
    public function setDeleteManager(DeleteRequestManager $deleteManager): void
    {
        $this->deleteManager = $deleteManager;
    }

    /**
     * @param InsertRequestManager $insertManager
     */
    public function setInsertManager(InsertRequestManager $insertManager): void
    {
        $this->insertManager = $insertManager;
    }

    /**
     * @param object $entity
     *
     * @return bool
     *
     * @throws AppException
     */
    public function updateOne(object $entity): bool
    {
        $mapping = $this->getMappingManager()->getEntityMapping(get_class($entity));
        $update  = $this->getPdoStatement($mapping);

        $update->execute($this->getDataManager()->getTableColumnsData($mapping, $entity));
        $this->updateRelations($mapping, $entity);

        return true;
    }

    /**
     * @param array $entities
     *
     * @return bool
     *
     * @throws AppException
     */
    public function updateMany(array $entities): bool
    {
        $mapping = $this->getMappingManager()->getEntityMapping(get_class($entities[array_key_first($entities)]));
        $update  = $this->getPdoStatement($mapping);

        foreach ($entities as $entity) {
            $update->execute($this->getDataManager()->getTableColumnsData($mapping, $entity));
            $this->updateRelations($mapping, $entity);
        }

        return true;
    }

    /**
     * @param EntityMapping $mapping
     *
     * @return bool|PDOStatement
     *
     * @throws AppException
     */
    private function getPdoStatement(EntityMapping $mapping)
    {
        $request = $this->builder->getUpdateRequest($mapping->getTableName(), $mapping->getTableColumns());
        return $this->getPdo()->prepare($request);
    }

    /**
     * @param EntityMapping $mapping
     * @param object        $entity
     *
     * @throws AppException
     */
    private function updateRelations(EntityMapping $mapping, object $entity): void
    {
        if (!$mapping->hasRelations()) {
            return;
        }

        foreach ($mapping->getRelationsMapping() as $attribute => $relation) {
            if ($relation->getType() === Relation::TYPE_ASSOCIATION) {
                $this->updateAssociationRelations($entity, $attribute, $relation, $mapping);
            }
        }
    }

    /**
     * @param object        $entity
     * @param string        $attribute
     * @param Relation      $relation
     * @param EntityMapping $mapping
     *
     * @throws AppException
     */
    private function updateAssociationRelations(
        object $entity,
        string $attribute,
        Relation $relation,
        EntityMapping $mapping
    ): void {
        $entityForeignKey = $this->builder->getUtils()->getAssociationForeignKey($mapping->getTableName());
        $idGetter         = $this->builder->getUtils()->getDefaultPrimaryGetterName();

        $this->deleteManager->deleteAssociations(
            $relation->getAssociation(),
            [$entityForeignKey => $entity->$idGetter()]
        );

        $this->insertManager->insertAssociationRelations($entity, $attribute, $relation, $mapping);
    }
}
