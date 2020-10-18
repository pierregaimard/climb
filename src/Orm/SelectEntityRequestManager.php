<?php

namespace Framework3\Orm;

use Framework3\Exception\AppException;
use Framework3\Orm\Annotation\Relation;

class SelectEntityRequestManager extends SelectRequestManager
{
    /**
     * @var SelectCollectionRequestManager
     */
    private SelectCollectionRequestManager $cRequestManager;

    /**
     * @param SelectCollectionRequestManager $cRequestManager
     */
    public function setCollectionRequestManager(SelectCollectionRequestManager $cRequestManager)
    {
        $this->cRequestManager = $cRequestManager;
    }

    /**
     * @param array $arg
     *
     * @return object|null
     *
     * @throws AppException
     */
    public function find(array $arg)
    {
        $mapping    = $this->getMappingManager()->getEntityMapping(($arg[self::ARG_CLASS]));
        $select     = $this->executeSelectRequest($arg, $mapping);
        $invertedBy = $this->getInvertedBy($arg);
        $entity     = $select->fetchObject($arg[self::ARG_CLASS]);

        if (!is_object($entity)) {
            return null;
        }

        $this->setRelations($mapping, $entity, $invertedBy);

        return $entity;
    }

    /**
     * @param EntityMapping $mapping
     * @param               $entity
     * @param string|null   $invertedBy
     *
     * @throws AppException
     */
    private function setRelations(EntityMapping $mapping, $entity, string $invertedBy = null): void
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
                    $this->setEntityRelation($entity, $attribute, $relation, $mapping->getRelationColumn($attribute));
                    break;

                case Relation::TYPE_COLLECTION:
                    $this->setCollectionRelation($entity, $attribute, $relation);
                    break;

                case Relation::TYPE_ASSOCIATION:
                    $this->setAssociationRelation($entity, $attribute, $relation, $mapping->getTableName());
            }
        }
    }

    /**
     * @param          $entity
     * @param string   $attribute
     * @param Relation $relation
     * @param string   $key
     *
     * @throws AppException
     */
    private function setEntityRelation($entity, string $attribute, Relation $relation, string $key)
    {
        $setter = $this->builder->getUtils()->getAttributeSetterName($attribute);

        $entity->$setter(
            $this->find([
                self::ARG_CLASS => $relation->getEntity(),
                self::ARG_SEARCH => [$this->builder->getUtils()->getDefaultPrimaryName() => $entity->$key],
                self::ARG_INVERTED_BY => $relation->getInvertedBy(),
            ])
        );
    }

    /**
     * @param          $entity
     * @param string   $attribute
     * @param Relation $relation
     *
     * @throws AppException
     */
    private function setCollectionRelation($entity, string $attribute, Relation $relation)
    {
        $setter      = $this->builder->getUtils()->getAttributeSetterName($attribute);
        $relationKey = $this->builder->getUtils()->getAttributeForeignKey($attribute);
        $idGetter    = $this->builder->getUtils()->getDefaultPrimaryGetterName();

        $entity->$setter(
            $this->cRequestManager->find([
                self::ARG_CLASS => $relation->getEntity(),
                self::ARG_SEARCH => [$relationKey => $entity->$idGetter()],
                self::ARG_INVERTED_BY => $relation->getInvertedBy()
            ])
        );
    }

    /**
     * @param          $entity
     * @param string   $attribute
     * @param Relation $relation
     * @param string   $table
     *
     * @throws AppException
     */
    private function setAssociationRelation($entity, string $attribute, Relation $relation, string $table)
    {
        $setter        = $this->builder->getUtils()->getAttributeSetterName($attribute);
        $foreignKey    = $this->builder->getUtils()->getAssociationForeignKey($table);
        $primaryGetter = $this->builder->getUtils()->getDefaultPrimaryGetterName();

        $entity->$setter(
            $this->cRequestManager->find([
                self::ARG_CLASS => $relation->getEntity(),
                self::ARG_SEARCH => [$foreignKey => $entity->$primaryGetter()],
                self::ARG_INVERTED_BY => $relation->getInvertedBy(),
                self::ARG_ASSOCIATION => $relation->getAssociation()
            ])
        );
    }
}
