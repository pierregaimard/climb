<?php

namespace Framework3\Orm;

use Framework3\Exception\AppException;

class SelectRequestBuilder extends RequestBuilder
{
    /**
     * @param string      $table
     * @param array|null  $search
     * @param array|null  $option
     * @param string|null $association
     *
     * @return string
     *
     * @throws AppException
     */
    public function getSelectRequest(
        string $table,
        array $search = null,
        array $option = null,
        string $association = null
    ) {
        $innerJoin  = null;
        $whereTable = $table;

        if ($association !== null) {
            $innerJoin  = $this->getAssociationParameter($table, $association);
            $whereTable = $association;
        }

        $where   = ($search !== null) ? $this->getSearchParameters($search, $whereTable) : null;
        $limit   = null;
        $orderBy = null;

        if (is_array($option)) {
            $limit   = $this->getLimitAndOffsetParameters($option);
            $orderBy = $this->getOrderByParameter($option);
        }

        return 'SELECT ' . $table . '.* FROM ' . $table . $innerJoin . $where . $orderBy . $limit;
    }

    /**
     * @param array $option
     *
     * @return string|null
     */
    private function getLimitAndOffsetParameters(array $option): ?string
    {
        if (!array_key_exists(EntityRepository::OPT_LIMIT, $option)) {
            return null;
        }

        $offset = null;

        if (array_key_exists(EntityRepository::OPT_OFFSET, $option)) {
            $offset = $option[EntityRepository::OPT_OFFSET] . ', ';
        }

        return ' LIMIT ' . $offset . $option[EntityRepository::OPT_LIMIT];
    }

    /**
     * @param array $option
     *
     * @return string|null
     */
    private function getOrderByParameter(array $option): ?string
    {
        if (!array_key_exists(EntityRepository::OPT_ORDER_BY, $option)) {
            return null;
        }

        $orderBy = $option[EntityRepository::OPT_ORDER_BY];

        $nbColumns   = count($orderBy);
        $count       = 0;
        $sqlOrderBy  = ' ORDER BY ';

        foreach ($orderBy as $key => $value) {
            $count++;
            $comma       = ($count < $nbColumns) ? ', ' : null;
            $sqlOrderBy .= $key . ' ' . $value . $comma;
        }

        return $sqlOrderBy;
    }

    /**
     * @param string $table
     * @param string $association
     *
     * @return string
     *
     * @throws AppException
     */
    private function getAssociationParameter(string $table, string $association): string
    {
        $primary    = $this->getUtils()->getDefaultPrimaryName();
        $foreignKey = $this->getUtils()->getAssociationForeignKey($table);

        return
            ' INNER JOIN ' . $association . ' ON ' .
            $table . '.' . $primary . ' = ' . $association . '.' . $foreignKey;
    }
}
