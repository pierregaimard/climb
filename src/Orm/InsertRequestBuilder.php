<?php

namespace Framework3\Orm;

use Framework3\Exception\AppException;

class InsertRequestBuilder extends RequestBuilder
{
    private const COLUMN     = 1;
    private const BIND_PARAM = 2;

    public function __construct(EntityMappingUtils $utils)
    {
        parent::__construct($utils);
    }

    /**
     * @param string $table
     * @param array  $columns
     *
     * @return string
     *
     * @throws AppException
     */
    public function getInsertRequest(string $table, array $columns): string
    {
        $columnParams = $this->getInsertColumnParameters($columns);

        return
            'INSERT INTO ' . $table .
            ' (' . $columnParams[self::COLUMN] . ') VALUES (' . $columnParams[self::BIND_PARAM] . ')'
            ;
    }

    /**
     * @param array $columns
     *
     * @return string[]
     *
     * @throws AppException
     */
    private function getInsertColumnParameters(array $columns): array
    {
        $this->getUtils()->removePrimaryColumn($columns);

        $nbColumns     = count($columns);
        $count         = 0;
        $sqlColumns    = '';
        $sqlBindParams = '';

        foreach ($columns as $column) {
            $count++;
            $virgule        = ($count < $nbColumns) ? ', ' : null;

            $sqlColumns    .= $column . $virgule;
            $sqlBindParams .= ':' . $column . $virgule;
        }

        return [
            self::COLUMN => $sqlColumns,
            self::BIND_PARAM => $sqlBindParams
        ];
    }
}
