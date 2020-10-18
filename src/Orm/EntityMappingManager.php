<?php

namespace Framework3\Orm;

use Framework3\Annotation\ReaderInterface;
use Framework3\Annotation\ReaderManagerInterface;
use Framework3\Exception\AppException;
use Framework3\Orm\Annotation\Relation;
use Framework3\Orm\Annotation\Table;
use Framework3\Orm\Annotation\Column;

class EntityMappingManager
{
    /**
     * @var ReaderManagerInterface
     */
    private ReaderManagerInterface $manager;

    /**
     * @var EntityMappingUtils
     */
    private EntityMappingUtils $utils;

    /**
     * @param ReaderManagerInterface $manager
     * @param EntityMappingUtils     $utils
     */
    public function __construct(ReaderManagerInterface $manager, EntityMappingUtils $utils)
    {
        $this->manager = $manager;
        $this->utils   = $utils;
    }

    /**
     * @param string $entity
     *
     * @return EntityMapping
     *
     * @throws AppException
     */
    public function getEntityMapping(string $entity): EntityMapping
    {
        $reader           = $this->manager->getReader($entity);
        $columnsMapping   = $reader->getPropertiesAnnotation(Column::TAG, true);
        $relationsMapping = $this->getRelationsMapping($reader);

        return new EntityMapping(
            $reader->getClassAnnotation(Table::TAG, true),
            $columnsMapping,
            $this->getColumns($columnsMapping),
            $relationsMapping,
            $this->getRelationColumns($relationsMapping)
        );
    }

    /**
     * @param ReaderInterface $reader
     *
     * @return array|null
     */
    private function getRelationsMapping(ReaderInterface $reader): ?array
    {
        $relations = $reader->getPropertiesAnnotation(Relation::TAG, true);

        if ($relations === null) {
            return null;
        }

        foreach ($relations as &$relation) {
            $relationReader = $this->manager->getReader($relation->getEntity());
            $table          = $relationReader->getClassAnnotation(Table::TAG, true);
            $relation->setTable($table->getName());
        }

        return $relations;
    }

    /**
     * @param array $columnsMapping
     *
     * @return array
     */
    private function getColumns(array $columnsMapping): array
    {
        $columns = [];

        foreach ($columnsMapping as $attribute => $column) {
            $columns[$attribute] = $column->getName();
        }

        return $columns;
    }

    /**
     *
     * Generate the FOREIGN KEY attribute name width default primary id and table name.
     *
     * This convention is used for all entities relations.
     *  `default primary = "id"` width relation attribute name = `userRole`
     *  will generate `id_user_role` key.
     *
     * @param array|null $relationsMapping
     *
     * @return array
     *
     * @throws AppException
     */
    private function getRelationColumns(array $relationsMapping = null): array
    {
        $relationColumns = [];

        if ($relationsMapping === null) {
            return $relationColumns;
        }

        foreach ($relationsMapping as $attribute => $relation) {
            if ($relation->getType() === Relation::TYPE_ENTITY) {
                $relationColumns[$attribute] = $this->utils->getAttributeForeignKey($attribute);
            }
        }

        return $relationColumns;
    }
}
