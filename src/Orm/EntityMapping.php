<?php

namespace Climb\Orm;

use Climb\Orm\Annotation\Column;
use Climb\Orm\Annotation\Relation;
use Climb\Orm\Annotation\Table;

class EntityMapping
{
    /**
     * @var Table
     */
    private Table $tableMapping;

    /**
     * @var Column[]
     */
    private array $columnsMapping;

    /**
     * @var Relation[]|null
     */
    private ?array $relationsMapping = null;

    /**
     * @var array
     */
    private array $columns = [];

    /**
     * For relations width `ENTITY` type
     *
     * @var array
     */
    private array $relationColumns = [];

    public function __construct(
        Table $tableMapping,
        array $columnsMapping,
        array $columns,
        array $relationsMapping = null,
        array $relationColumns = []
    ) {
        $this->tableMapping     = $tableMapping;
        $this->columnsMapping   = $columnsMapping;
        $this->columns          = $columns;
        $this->relationsMapping = $relationsMapping;
        $this->relationColumns  = $relationColumns;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableMapping->getName();
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getRelationColumns(): array
    {
        return $this->relationColumns;
    }

    /**
     * @return bool
     */
    public function hasRelations(): bool
    {
        return $this->relationsMapping !== null;
    }

    /**
     * @return bool
     */
    public function hasRelationColumns(): bool
    {
        return !empty($this->relationColumns);
    }

    /**
     * @return array
     */
    public function getTableColumns(): array
    {
        return array_merge($this->columns, $this->relationColumns);
    }

    /**
     * @return Relation[]|null
     */
    public function getRelationsMapping(): ?array
    {
        return $this->relationsMapping;
    }

    /**
     * @param string $attribute
     *
     * @return mixed|null
     */
    public function getRelationColumn(string $attribute)
    {
        if (!array_key_exists($attribute, $this->relationColumns)) {
            return null;
        }

        return $this->relationColumns[$attribute];
    }
}
