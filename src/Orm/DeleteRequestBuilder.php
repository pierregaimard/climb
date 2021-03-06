<?php

namespace Climb\Orm;

use Climb\Exception\AppException;

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
            $search = [$this->getUtils()->getDefaultDbPrimaryName() => null];
        }

        return 'DELETE FROM ' . $table . $this->getSearchParameters($search, $table);
    }
}
