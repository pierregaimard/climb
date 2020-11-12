<?php

namespace Climb\Orm;

use Climb\Exception\AppException;

class UpdateRequestBuilder extends RequestBuilder
{
    /**
     * @param string     $table
     * @param array      $columns
     * @param array|null $search
     *
     * @return string
     *
     * @throws AppException
     */
    public function getUpdateRequest(string $table, array $columns, array $search = null)
    {
        if ($search === null) {
            $search = [$this->getUtils()->getDefaultDbPrimaryName() => null];
        }

        return
            'UPDATE ' . $table .
            ' SET ' . $this->getUpdateColumnParameters($columns) . $this->getSearchParameters($search, $table)
            ;
    }

    /**
     * @param array $columns
     *
     * @return string
     *
     * @throws AppException
     */
    private function getUpdateColumnParameters(array $columns): string
    {
        $this->getUtils()->removePrimaryColumn($columns);

        $nbColumns     = count($columns);
        $count         = 0;
        $sqlSetColumns = '';

        foreach ($columns as $column) {
            $count++;
            $virgule        = ($count < $nbColumns) ? ', ' : null;
            $sqlSetColumns .= $column . ' = :' . $column . $virgule;
        }

        return $sqlSetColumns;
    }
}
