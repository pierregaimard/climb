<?php

namespace Framework3\Orm;

use Framework3\Config\ConfigBag;
use Framework3\Exception\AppException;

class EntityRepositoryUtils
{
    /**
     * @var array
     */
    private array $config;

    /**
     * @param ConfigBag $ormConfig
     *
     * @throws AppException
     */
    public function __construct(ConfigBag $ormConfig)
    {
        $this->config = $ormConfig->get('GLOBALS', true);
    }

    /**
     * @param string $entity
     *
     * @return string
     *
     * @throws AppException
     */
    public function getRepositoryClass(string $entity): string
    {
        $repository = str_replace(
            '\\' . $this->getEntitySubDir() . '\\',
            '\\' . $this->getRepositorySubDir() . '\\',
            $entity
        ) . $this->getRepositorySuffix();

        $this->checkRepositoryClass($repository, $entity);

        return $repository;
    }

    /**
     * @return string
     */
    private function getEntitySubDir(): string
    {
        return $this->config['ENTITY_SUB_DIR'];
    }

    /**
     * @return string
     */
    private function getRepositorySubDir(): string
    {
        return $this->config['REPOSITORY_SUB_DIR'];
    }

    /**
     * @return string
     */
    private function getRepositorySuffix(): string
    {
        return $this->config['REPOSITORY_SUFFIX'];
    }

    /**
     * @param string $repository
     * @param string $entity
     *
     * @throws AppException
     */
    private function checkRepositoryClass(string $repository, string $entity): void
    {
        if (!class_exists($repository)) {
            throw new AppException(
                AppException::TYPE_ORM,
                'Repository exception.',
                sprintf('Repository is missing for entity "%s"', $entity)
            );
        }
    }
}
