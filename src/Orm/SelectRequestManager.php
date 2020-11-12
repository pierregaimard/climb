<?php

namespace Climb\Orm;

use PDO;
use PDOStatement;
use Climb\Exception\AppException;

class SelectRequestManager extends RequestManager
{
    public const ARG_OPTIONS     = 'OPTIONS';
    public const ARG_SEARCH      = 'SEARCH';
    public const ARG_CLASS       = 'CLASS';
    public const ARG_INVERTED_BY = 'INVERTED_BY';
    public const ARG_ASSOCIATION = 'ASSOCIATION';
    public const ARG_REQUEST     = 'REQUEST';

    /**
     * @var SelectRequestBuilder
     */
    protected SelectRequestBuilder $builder;

    /**
     * @param PDO                  $pdo
     * @param EntityMappingManager $mappingManager
     * @param EntityDataManager    $dataManager
     * @param SelectRequestBuilder $builder
     */
    public function __construct(
        PDO $pdo,
        EntityMappingManager $mappingManager,
        EntityDataManager $dataManager,
        SelectRequestBuilder $builder
    ) {
        parent::__construct($pdo, $mappingManager, $dataManager);
        $this->builder = $builder;
    }

    /**
     * @param array         $arg
     * @param EntityMapping $mapping
     *
     * @return bool|PDOStatement
     *
     * @throws AppException
     */
    protected function executeSelectRequest(array $arg, EntityMapping $mapping)
    {
        $options     = (array_key_exists(self::ARG_OPTIONS, $arg)) ? $arg[self::ARG_OPTIONS] : null;
        $search      = (array_key_exists(self::ARG_SEARCH, $arg)) ? $arg[self::ARG_SEARCH] : null;
        $association = (array_key_exists(self::ARG_ASSOCIATION, $arg)) ? $arg[self::ARG_ASSOCIATION] : null;
        $request     = (array_key_exists(self::ARG_REQUEST, $arg)) ?
            $arg[self::ARG_REQUEST] :
            $this->builder->getSelectRequest($mapping->getTableName(), $search, $options, $association);
        $select      = $this->getPdo()->prepare($request);

        $select->execute($search);

        return $select;
    }

    /**
     * @param array $arg
     *
     * @return string|null
     */
    protected function getInvertedBy(array $arg): ?string
    {
        return (array_key_exists(self::ARG_INVERTED_BY, $arg)) ? $arg[self::ARG_INVERTED_BY] : null;
    }
}
