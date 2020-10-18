<?php

namespace Framework3\Orm\Annotation;

use Framework3\Annotation\AnnotationInterface;

class Relation implements AnnotationInterface
{
    public const TAG              = 'Relation';
    public const TYPE_ENTITY      = 'entity';
    public const TYPE_COLLECTION  = 'collection';
    public const TYPE_ASSOCIATION = 'association';

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $entity;

    /**
     * @var string|null
     */
    private ?string $invertedBy = null;

    /**
     * @var string
     */
    private string $association;

    /**
     * @var string
     */
    private string $table;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     */
    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @return string|null
     */
    public function getInvertedBy(): ?string
    {
        return $this->invertedBy;
    }

    /**
     * @param string $invertedBy
     */
    public function setInvertedBy(string $invertedBy): void
    {
        $this->invertedBy = $invertedBy;
    }

    /**
     * @return string
     */
    public function getAssociation(): string
    {
        return $this->association;
    }

    /**
     * @param string $association
     */
    public function setAssociation(string $association): void
    {
        $this->association = $association;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param string $table
     */
    public function setTable(string $table): void
    {
        $this->table = $table;
    }
}
