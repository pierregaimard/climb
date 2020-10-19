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
        $key = array_search($this->getDefaultPrimaryName(), $columns);
        
        if ($key !== false) {
            unset($columns[$key]);
        }
    }

    /**
     * @return string
     *
     * @throws AppException
     */
    public function getDefaultPrimaryName(): string
    {
        return $this->config->get('GLOBALS', true)['DEFAULTS']['PRIMARY']['NAME'];
    }

    /**
     * @return string
     *
     * @throws AppException
     */
    public function getDefaultPrimaryGetterName(): string
    {
        return 'get' . ucfirst(strtolower($this->getDefaultPrimaryName()));
    }

    /**
     * @return string
     *
     * @throws AppException
     */
    public function getDefaultPrimarySetterName(): string
    {
        return 'set' . ucfirst(strtolower($this->getDefaultPrimaryName()));
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
        return $this->getDefaultPrimaryName() . '_' . $table;
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
        $key   = $this->getDefaultPrimaryName() . '_';

        foreach ($array as $char) {
            if (ctype_upper($char)) {
                $char = '_' . strtolower($char);
            }

            $key .= $char;
        }

        return $key;
    }
}
