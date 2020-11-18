<?php

namespace Climb\Orm;

use Climb\Exception\AppException;

class EntityRepository
{
    public const OPT_LIMIT    = 'LIMIT';
    public const OPT_OFFSET   = 'OFFSET';
    public const OPT_ORDER_BY = 'ORDER_BY';

    /**
     * @var string Entity class name
     */
    private string $entity;

    /**
     * @var SelectEntityRequestManager $eSelectManager
     */
    private SelectEntityRequestManager $eSelectManager;

    /**
     * @var SelectCollectionRequestManager
     */
    private SelectCollectionRequestManager $cSelectManager;

    /**
     * @var EntityMappingUtils
     */
    private EntityMappingUtils $utils;

    public function __construct(
        string $entity,
        SelectEntityRequestManager $eSelectManager,
        SelectCollectionRequestManager $cSelectManager,
        EntityMappingUtils $utils
    ) {
        $this->entity         = $entity;
        $this->eSelectManager = $eSelectManager;
        $this->cSelectManager = $cSelectManager;
        $this->utils          = $utils;
        $this->eSelectManager->setCollectionRequestManager($cSelectManager);
    }

    /**
     * @param int $primary
     *
     * @return object|null
     *
     * @throws AppException
     */
    public function findOne(int $primary): ?object
    {
        return $this->eSelectManager->find([
            SelectRequestManager::ARG_CLASS => $this->entity,
            SelectRequestManager::ARG_SEARCH => [$this->utils->getDefaultDbPrimaryName() => $primary],
        ]);
    }

    /**
     * @param array $search
     *
     * @return object|null
     *
     * @throws AppException
     */
    public function findOneBy(array $search): ?object
    {
        return $this->eSelectManager->find([
            SelectRequestManager::ARG_CLASS => $this->entity,
            SelectRequestManager::ARG_SEARCH => $search,
        ]);
    }

    /**
     * @param string $request
     * @param array  $search
     *
     * @throws AppException
     */
    protected function findOneByRequest(string $request, array $search)
    {
        return $this->eSelectManager->find([
            SelectRequestManager::ARG_CLASS => $this->entity,
            SelectRequestManager::ARG_REQUEST => $request,
            SelectRequestManager::ARG_SEARCH => $search,
        ]);
    }

    /**
     * @param array|null $option
     *
     * @return array|null
     *
     * @throws AppException
     */
    public function findAll(array $option = null): ?array
    {
        return $this->cSelectManager->find([
            SelectRequestManager::ARG_CLASS => $this->entity,
            SelectRequestManager::ARG_OPTIONS => $option,
        ]);
    }

    /**
     * @param array      $search
     * @param array|null $option
     *
     * @return array|null
     *
     * @throws AppException
     */
    public function findBy(array $search, array $option = null): ?array
    {
        return $this->cSelectManager->find([
            SelectRequestManager::ARG_CLASS => $this->entity,
            SelectRequestManager::ARG_SEARCH => $search,
            SelectRequestManager::ARG_OPTIONS => $option,
        ]);
    }

    /**
     * @param string $request
     * @param array  $search
     *
     * @return array|null
     *
     * @throws AppException
     */
    protected function findByRequest(string $request, array $search)
    {
        return $this->cSelectManager->find([
            SelectRequestManager::ARG_CLASS => $this->entity,
            SelectRequestManager::ARG_REQUEST => $request,
            SelectRequestManager::ARG_SEARCH => $search,
        ]);
    }

    /**
     * @param array $search
     *
     * @return bool
     *
     * @throws AppException
     */
    public function has(array $search): bool
    {
        return is_object($this->findOneBy($search));
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }
}
