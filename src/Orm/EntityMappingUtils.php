<?php

namespace Climb\Orm;

use Climb\Config\ConfigBag;
use Climb\Exception\AppException;

class EntityMappingUtils
{
    /**
     * @var ConfigBag
     */
    private ConfigBag $config;

    /**
     * RequestBuilder constructor.
     *
     * @param ConfigBag $config
     */
    public function __construct(ConfigBag $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $columns
     *
     * @throws AppException
     */
    public function removePrimaryColumn(array &$columns): void
    {
        $key = array_search($this->getDefaultDbPrimaryName(), $columns);
        
        if ($key !== false) {
            unset($columns[$key]);
        }
    }

    /**
     * @return string
     *
     * @throws AppException
     */
    public function getDefaultDbPrimaryName(): string
    {
        return $this->config->get('GLOBALS', true)['DEFAULTS']['PRIMARY']['DB_NAME'];
    }

    /**
     * @return string
     *
     * @throws AppException
     */
    private function getDefaultEntityPrimaryName(): string
    {
        return $this->config->get('GLOBALS', true)['DEFAULTS']['PRIMARY']['ENTITY_NAME'];
    }

    /**
     * @return string
     *
     * @throws AppException
     */
    public function getDefaultPrimaryGetterName(): string
    {
        return 'get' . ucfirst(strtolower($this->getDefaultEntityPrimaryName()));
    }

    /**
     * @return string
     *
     * @throws AppException
     */
    public function getDefaultPrimarySetterName(): string
    {
        return 'set' . ucfirst(strtolower($this->getDefaultEntityPrimaryName()));
    }

    /**
     * @param string $attribute
     *
     * @return string
     */
    public function getAttributeSetterName(string $attribute): string
    {
        return 'set' . ucfirst($attribute);
    }

    /**
     * @param string $attribute
     *
     * @return string
     */
    public function getAttributeGetterName(string $attribute): string
    {
        return 'get' . ucfirst($attribute);
    }

    /**
     * @param string $table
     *
     * @return string
     *
     * @throws AppException
     */
    public function getAssociationForeignKey(string $table): string
    {
        return $this->getDefaultDbPrimaryName() . '_' . $table;
    }

    /**
     * @param string $attribute
     *
     * @return string
     *
     * @throws AppException
     */
    public function getAttributeForeignKey(string $attribute): string
    {
        $array = str_split($attribute);
        $key   = $this->getDefaultDbPrimaryName() . '_';

        foreach ($array as $char) {
            if (ctype_upper($char)) {
                $char = '_' . strtolower($char);
            }

            $key .= $char;
        }

        return $key;
    }
}
