<?php

namespace Framework3\Orm\Annotation;

use Framework3\Annotation\AnnotationInterface;

class Column implements AnnotationInterface
{
    public const TAG = 'Column';

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $type;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

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
}
