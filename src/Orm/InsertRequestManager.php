<?php

namespace Climb\Orm;

use Climb\Orm\Annotation\Relation;
use PDO;
use PDOStatement;
use Climb\Exception\AppException;

class InsertRequestManager extends RequestManager
{
    /**
     * @var InsertRequestBuilder
     */
    private InsertRequestBuilder $builder;

    public function __construct(
        PDO $pdo,
        EntityMappingManager $mappingManager,
        EntityDataManager $dataManager,
        InsertRequestBuilder $builder
    ) {
        parent::__construct($pdo, $mappingManager, $dataManager);
        $this->builder = $builder;
    }

    /**
     * @param object $entity
     * @param bool   $flushRelations
     *
     * @return int
     *
     * @throws AppException
     */
    public function insertOne(object $entity, $flushRelations = true): int
    {
        $mapping = $this->getMappingManager()->getEntityMapping(get_class($entity));
        $insert  = $this->getPdoStatement($mapping);

        $insert->execute($this->getDataManager()->getTableColumnsData($mapping, $entity, true));
        $lastInsertId = $this->getPdo()->lastInsertId();
        $this->setEntityId($entity, $lastInsertId);

        if ($flushRelations) {
            $this->insertAssociations($mapping, $entity);
        }

        return $lastInsertId;
    }

    /**
     * @param array $entities
     * @param bool  $flushRelations
     *
     * @return array
     *
     * @throws AppException
     */
    public function insertMany(array $entities, $flushRelations = true): array
    {
        $mapping     = $this->getMappingManager()->getEntityMapping(get_class($entities[array_key_first($entities)]));
        $insert      = $this->getPdoStatement($mapping);
        $insertIdTab = [];

        foreach ($entities as $entity) {
            $insert->execute($this->getDataManager()->getTableColumnsData($mapping, $entity, true));
            $lastInsertId = $this->getPdo()->lastInsertId();
            $this->setEntityId($entity, $lastInsertId);

            if ($flushRelations) {
                $this->insertAssociations($mapping, $entity);
            }

            $insertIdTab[] = $this->getPdo()->lastInsertId();
        }

        return $insertIdTab;
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
        $request = $this->builder->getInsertRequest($mapping->getTableName(), $mapping->getTableColumns());

        return $this->getPdo()->prepare($request);
    }

    /**
     * @param object $entity
     * @param int    $primary
     *
     * @throws AppException
     */
    private function setEntityId(object $entity, int $primary): void
    {
        $idSetter = $this->builder->getUtils()->getDefaultPrimarySetterName();
        $entity->$idSetter($primary);
    }

    /**
     * @param EntityMapping $mapping
     * @param object        $entity
     *
     * @throws AppException
     */
    private function insertAssociations(EntityMapping $mapping, object $entity): void
    {
        if (!$mapping->hasRelations()) {
            return;
        }

        foreach ($mapping->getRelationsMapping() as $attribute => $relation) {
            if ($relation->getType() === Relation::TYPE_ASSOCIATION) {
                $this->insertAssociationRelations($entity, $attribute, $relation, $mapping);
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
    public function insertAssociationRelations(
        object $entity,
        string $attribute,
        Relation $relation,
        EntityMapping $mapping
    ): void {
        $entityForeignKey   = $this->builder->getUtils()->getAssociationForeignKey($mapping->getTableName());
        $relationForeignKey = $this->builder->getUtils()->getAssociationForeignKey($relation->getTable());
        $relationGetter     = $this->builder->getUtils()->getAttributeGetterName($attribute);
        $idGetter           = $this->builder->getUtils()->getDefaultPrimaryGetterName();

        $request = $this->builder->getInsertRequest(
            $relation->getAssociation(),
            [$entityForeignKey, $relationForeignKey]
        );

        $insert = $this->getPdo()->prepare($request);

        foreach ($entity->$relationGetter() as $relatedEntity) {
            $data = [
                $entityForeignKey => $entity->$idGetter(),
                $relationForeignKey => $relatedEntity->$idGetter()
            ];

            $insert->execute($data);
        }
    }
}
