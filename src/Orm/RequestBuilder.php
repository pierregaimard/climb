<?php

namespace Framework3\Orm;

class RequestBuilder
{
    /**
     * @var EntityMappingUtils
     */
    private EntityMappingUtils $utils;

    public function __construct(EntityMappingUtils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * @return EntityMappingUtils
     */
    public function getUtils(): EntityMappingUtils
    {
        return $this->utils;
    }

    /**
     * @param array  $search
     * @param string $table
     *
     * @return string|null
     */
    protected function getSearchParameters(array $search, string $table): ?string
    {
        $nbColumns = count($search);
        $count     = 0;
        $sqlWhere  = ' WHERE ';


        foreach ($search as $item) {
            $count++;
            $and       = ($count < $nbColumns) ? ' and ' : null;
            $key       = array_search($item, $search);
            $sqlWhere .= $table . '.' . $key . ' = :' . $key . $and;
        }

        return $sqlWhere;
    }
}
