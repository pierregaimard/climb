<?php

/**
 * This service replaces a referenced var by it's value
 *
 * e.g.
 *  MY_VAR="root"
 *  MY_SECOND_VAR="${MY_VAR}/test"
 *
 *  in this case, ${MY_VAR} will be replaced by "root".
 *  and will return MY_SECOND_VAR="root/test"
 */

namespace Climb\Env;

class EnvReferenceParser
{
    /**
     * @param array $rawData
     *
     * @return array
     */
    public function setReferences(array $rawData): array
    {
        foreach ($rawData as $key => $value) {
            preg_match('#\$\{([a-zA-Z]+(_?[a-zA-Z0-9]+)*)\}#', $value, $matches);
            if (!empty($matches)) {
                $rawData[$key] = str_replace($matches[0], $rawData[$matches[1]], $value);
            }
        }

        return $rawData;
    }
}
