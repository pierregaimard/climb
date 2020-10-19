<?php

namespace Climb\Orm;

use Climb\Exception\AppException;

class Orm
{
    /**
     * @var ReadingRequestManagerContainer
     */
    private ReadingRequestManagerContainer $readingContainer;

    /**
     * @var WritingRequestManagerContainer
     */
    private WritingRequestManagerContainer $writingContainer;

    /**
     * @var EntityRepositoryUtils
     */
    private EntityRepositoryUtils $repositoryUtils;

    /**
     * @var EntityMappingUtils
     */
    private EntityMappingUtils $mappingUtils;

    /**
     * @param ReadingRequestManagerContainer $readingContainer
     * @param WritingRequestManagerContainer $writingContainer
     * @param EntityRepositoryUtils          $repositoryUtils
     * @param EntityMappingUtils             $mappingUtils
     */
    public function __construct(
        ReadingRequestManagerContainer $readingContainer,
        WritingRequestManagerContainer $writingContainer,
        EntityRepositoryUtils $repositoryUtils,
        EntityMappingUtils $mappingUtils
    ) {
        $this->readingContainer = $readingContainer;
        $this->writingContainer = $writingContainer;
        $this->repositoryUtils  = $repositoryUtils;
        $this->mappingUtils     = $mappingUtils;
    }

    /**
     * @param $connection
     *
     * @return EntityManager
     *
     * @throws AppException
     */
    public function getManager($connection): EntityManager
    {
        return new EntityManager(
            $this->writingContainer->getInsertManager($connection),
            $this->writingContainer->getUpdateManager($connection),
            $this->writingContainer->getDeleteManager($connection),
            $this->readingContainer->getSelectEntityManager($connection),
            $this->readingContainer->getSelectCollectionManager($connection),
            $this->repositoryUtils,
            $this->mappingUtils
        );
    }
}
