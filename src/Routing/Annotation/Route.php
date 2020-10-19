<?php

/**
 * Represents a route annotation.
 */

namespace Climb\Routing\Annotation;

use Climb\Annotation\AnnotationInterface;

class Route implements AnnotationInterface
{
    public const TAG = 'Route';

    /**
     * Route name
     *
     * @var string|null
     */
    private ?string $name;

    /**
     * Route relative path
     *
     * @example `"/admin/home"`
     *
     * @var string|null
     */
    private ?string $path;

    /**
     * Array of regex for variable path data.
     *
     * Where the key is the variable name and the value contains the regex.
     *
     * @example `@Route(path = "/user/{id}", regex={"id" = "[1-9][0-9]{1,4}"}`
     *
     * @var array
     */
    private array $regex = [];

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
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return array|null
     */
    public function getRegex(): ?array
    {
        return $this->regex;
    }

    /**
     * @param array $regex
     */
    public function setRegex(array $regex): void
    {
        $this->regex = $regex;
    }
}
