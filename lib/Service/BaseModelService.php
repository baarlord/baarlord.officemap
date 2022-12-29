<?php

namespace Baarlord\OfficeMap\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

abstract class BaseModelService implements SaveModelServiceInterface
{
    private EntityManager $entityManager;
    private EntityRepository $repository;

    /**
     * @param EntityRepository $repository
     */
    public function __construct(EntityManager $entityManager, EntityRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(object $model): void
    {
        $this->entityManager->persist($model);
        $this->entityManager->flush();
    }
}
