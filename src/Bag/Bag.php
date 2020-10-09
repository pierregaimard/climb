<?php

namespace Framework3\Bag;

use Generator;
use Traversable;
use IteratorAggregate;

class Bag implements IteratorAggregate
{
    /**
     * array of bag items
     *
     * @var array $bag
     */
    protected array $bag = [];

    /**
     * Bag constructor.
     *
     * @param array|null $data
     */
    public function __construct(?array $data = null)
    {
        if ($data !== null) {
            $this->bag = $data;
        };
    }

    /**
     * @return Generator|Traversable
     */
    public function getIterator()
    {
        yield from $this->bag;
    }

    /**
     * Checks if an item exists in the bag.
     *
     * @param $item
     *
     * @return bool
     */
    public function has($item): bool
    {
        return array_key_exists($item, $this->bag);
    }

    /**
     * Returns an item value if exists, false if it don't.
     *
     * @param $item
     *
     * @return mixed|false
     */
    public function get($item)
    {
        return ($this->has($item)) ? $this->bag[$item] : false;
    }

    /**
     * Add an item to the bag.
     *
     * @param $item
     * @param $value
     */
    public function add($item, $value): void
    {
        $this->bag[$item] = $value;
    }

    /**
     * Remove an item from the bag. Returns true if deleted, false if item don't exist.
     *
     * @param $item
     *
     * @return bool
     */
    public function remove($item): bool
    {
        if ($this->has($item)) {
            unset($this->bag[$item]);
            return true;
        }

        return false;
    }

    /**
     * returns all the bag data
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->bag;
    }

    /**
     * returns all the bag data
     *
     * @param  array  $data
     */
    public function setAll(array $data): void
    {
        $this->bag = $data;
    }

    /**
     * remove all the items of the bag.
     */
    public function removeAll(): void
    {
        $this->bag = [];
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->bag);
    }
}
