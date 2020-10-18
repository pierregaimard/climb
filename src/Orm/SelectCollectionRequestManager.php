<?php

namespace Framework3\Orm;

use Framework3\Bag\Bag;
use Framework3\Exception\AppException;
use Framework3\Orm\Annotation\Relation;
use PDO;

class SelectCollectionRequestManager extends SelectRequestManager
{
    /**
     * @var Bag
     */
    private Bag $entities;

    public function __construct(
        PDO $pdo,
        EntityMappingManager $mappingManager,
        EntityDataManager $dataManager,
        SelectRequestBuilder $builder
    ) {
        parent::__construct($pdo, $mappingManager, $dataManager, $builder);
        $this->entities = new Bag();
    }

    /**
     * @param array $arg
     *
     * @return mixed
     *
     * @throws AppException
     */
    public function find(array $arg)
    {
        $mapping    = $this->getMappingManager()->getEntityMapping(($arg[self::ARG_CLASS]));
        $select     = $this->executeSelectRequest($arg, $mapping);
        $invertedBy = $this->getInvertedBy($arg);
        $entities   = $this->getDataManager()->getEntityTab(
            $select->fetchAll(PDO::FETCH_CLASS, $arg[self::ARG_CLASS])
        );
        
        if (!empty($entities)) {
            $this->setRelations($mapping, $entities, $invertedBy);
        }

        return $entities;
    }

    /**
     * @param EntityMapping $mapping
     * @param array         $entities
     * @param string|null   $invertedBy
     *
     * @throws AppException
     */
    private function setRelations(EntityMapping $mapping, array $entities, string $invertedBy = null): void
    {
        if (!$mapping->hasRelations()) {
            return;
        }

        foreach ($mapping->getRelationsMapping() as $attribute => $relation) {
            if ($invertedBy !== null && $attribute === $invertedBy) {
                continue;
            }

            switch ($relation->getType()) {
                case Relation::TYPE_ENTITY:
                    $this->setEntityRelation($entities, $attribute, $relation);
                    break;

                case Relation::TYPE_COLLECTION:
                    $this->setCollectionRelation($entities, $attribute, $relation);
                    break;

                case Relation::TYPE_ASSOCIATION:
                    $this->setAssociationRelation($entities, $attribute, $relation, $mapping->getTableName());
            }
        }
    }

    /**
     * @param array    $entities
     * @param string   $attribute
     * @param Relation $relation
     *
     * @throws AppException
     */
    private function setEntityRelation(array $entities, string $attribute, Relation $relation)
    {
        $class = $relation->getEntity();

        if (!$this->entities->has($class)) {
            $this->entities->add(
                $class,
                $this->find([
                    self::ARG_CLASS => $class,
                    self::ARG_INVERTED_BY => $relation->getInvertedBy(),
                ])
            );
        }

        $setter     = $this->builder->getUtils()->getAttributeSetterName($attribute);
        $foreignKey = $this->builder->getUtils()->getAttributeForeignKey($attribute);

        foreach ($entities as $entity) {
            $entity->$setter($this->entities->get($class)[$entity->$foreignKey]);
        }
    }

    /**
     * @param array    $entities
     * @param string   $attribute
     * @param Relation $relation
     *
     * @throws AppException
     */
    private function setCollectionRelation(array $entities, string $attribute, Relation $relation)
    {
        $setter      = $this->builder->getUtils()->getAttributeSetterName($attribute);
        $relationKey = $this->builder->getUtils()->getAttributeForeignKey($attribute);
        $idGetter    = $this->builder->getUtils()->getDefaultPrimaryGetterName();

        foreach ($entities as $entity) {
            $entity->$setter(
                $this->find([
                    self::ARG_CLASS => $relation->getEntity(),
                    self::ARG_SEARCH => [$relationKey => $entity->$idGetter()],
                    self::ARG_INVERTED_BY => $relation->getInvertedBy()
                ])
            );
        }
    }

    /**
     * @param array    $entities
     * @param string   $attribute
     * @param Relation $relation
     * @param string   $table
     *
     * @throws AppException
     */
    private function setAssociationRelation(array $entities, string $attribute, Relation $relation, string $table)
    {
        $setter        = $this->builder->getUtils()->getAttributeSetterName($attribute);
        $foreignKey    = $this->builder->getUtils()->getAssociationForeignKey($table);
        $primaryGetter = $this->builder->getUtils()->getDefaultPrimaryGetterName();

        foreach ($entities as $entity) {
            $entity->$setter(
                $this->find([
                    self::ARG_CLASS => $relation->getEntity(),
                    self::ARG_SEARCH => [$foreignKey => $entity->$primaryGetter()],
                    self::ARG_INVERTED_BY => $relation->getInvertedBy(),
                    self::ARG_ASSOCIATION => $relation->getAssociation()
                ])
            );
        }
    }
}
