<?php

namespace Climb\Orm;

use Climb\Exception\AppException;

class EntityDataManager
{
    /**
     * @var EntityMappingUtils
     */
    private EntityMappingUtils $utils;

    /**
     * @var string
     */
    private string $primary;

    /**
     * @param EntityMappingUtils $utils
     *
     * @throws AppException
     */
    public function __construct(EntityMappingUtils $utils)
    {
        $this->utils   = $utils;
        $this->primary = $this->utils->getDefaultDbPrimaryName();
    }

    /**
     * @param array  $columns
     * @param string $column
     * @param object $entity
     *
     * @return mixed
     */
    public function getColumnData(array $columns, string $column, object $entity)
    {
        $attribute = array_search($column, $columns);
        $getter    = 'get' . ucfirst(strtolower($attribute));

        return $entity->$getter();
    }

    /**
     * @param EntityMapping $mapping
     * @param object        $entity
     * @param bool          $excludePrimary
     *
     * @return array
     *
     * @throws AppException
     */
    public function getTableColumnsData(EntityMapping $mapping, object $entity, bool $excludePrimary = false): array
    {
        $columnsData         = $this->getColumnsData($mapping, $entity, $excludePrimary);
        $relationColumnsData = $this->getRelationsColumnsData($mapping, $entity);

        if ($relationColumnsData === null) {
            return $columnsData;
        }

        return array_merge($columnsData, $relationColumnsData);
    }

    /**
     * @param EntityMapping $mapping
     * @param object        $entity
     *
     * @return array
     */
    public function getPrimaryColumnData(EntityMapping $mapping, object $entity): array
    {
        return [$this->primary => $this->getColumnData($mapping->getColumns(), $this->primary, $entity)];
    }

    /**
     * @param EntityMapping $mapping
     * @param object        $entity
     * @param bool          $excludePrimary
     *
     * @return array
     *
     * @throws AppException
     */
    public function getColumnsData(EntityMapping $mapping, object $entity, bool $excludePrimary = false): array
    {
        $data    = [];
        $columns = $mapping->getColumns();

        if ($excludePrimary) {
            $this->utils->removePrimaryColumn($columns);
        }

        foreach ($columns as $column) {
            $data[$column] = $this->getColumnData($mapping->getColumns(), $column, $entity);
        }

        return $data;
    }

    /**
     * @param EntityMapping $mapping
     * @param object        $entity
     *
     * @return array|null
     */
    private function getRelationsColumnsData(EntityMapping $mapping, object $entity): ?array
    {
        if (!$mapping->hasRelationColumns()) {
            return null;
        }

        $data = [];

        foreach ($mapping->getRelationColumns() as $column) {
            $relation = $this->getColumnData($mapping->getRelationColumns(), $column, $entity);

            $data[$column] = (is_object($relation)) ?
                $this->getPrimaryColumnData($mapping, $relation)[$this->primary] : null;
        }

        return $data;
    }

    /**
     * @param string        $entity
     * @param EntityMapping $mapping
     * @param array         $data
     *
     * @return object
     */
    public function getHydratedEntity(string $entity, EntityMapping $mapping, array $data): object
    {
        $entity = new $entity();
        foreach ($mapping->getColumns() as $attribute => $column) {
            $setter = $this->utils->getAttributeSetterName($attribute);
            $entity->$setter($data[$column]);
        }

        foreach ($mapping->getRelationColumns() as $relationColumn) {
            $entity->$relationColumn = $data[$relationColumn];
        }

        return $entity;
    }

    /**
     * @param string        $entity
     * @param EntityMapping $mapping
     * @param array         $data
     *
     * @return array
     *
     * @throws AppException
     */
    public function getHydratedEntities(string $entity, EntityMapping $mapping, array $data): array
    {
        $entities = [];
        foreach ($data as $entityData) {
            $entities[$entityData[$this->utils->getDefaultDbPrimaryName()]] = $this->getHydratedEntity(
                $entity,
                $mapping,
                $entityData
            );
        }

        return $entities;
    }
}
