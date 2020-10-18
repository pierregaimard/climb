<?php

namespace Framework3\Orm;

use Framework3\Exception\AppException;

class DeleteRequestBuilder extends RequestBuilder
{
    /**
     * @param string     $table
     * @param array|null $search
     *
     * @return string
     * @throws AppException
     */
    public function getDeleteRequest(string $table, array $search = null)
    {
        if ($search === null) {
            $search = [$this->getUtils()->getDefaultPrimaryName() => null];
        }

        return 'DELETE FROM ' . $table . $this->getSearchParameters($search, $table);
    }
}
