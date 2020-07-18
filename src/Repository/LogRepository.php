<?php

declare(strict_types=1);

namespace SimplePhpCrud\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use SimplePhpCrud\Entity\Log;

/**
 * Class LogRepository
 */
class LogRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * LogRepository constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \SimplePhpCrud\Entity\Log $log
     */
    public function persist(Log $log): void
    {
        $this->entityManager->merge($log);
        $this->entityManager->flush();
    }

    /**
     * @param \Doctrine\Common\Collections\Criteria $criteria
     *
     * @return array
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function findBy(Criteria $criteria): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('l.userId,l.changedDb,l.change')
           ->from(Log::class, 'l')
           ->addCriteria($criteria);

        return $qb->getQuery()
                  ->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @param \Doctrine\Common\Collections\Criteria $criteria
     *
     * @return int
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function deleteBy(Criteria $criteria): int
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->delete(Log::class, 'log')
           ->addCriteria($criteria);

        return $qb->getQuery()
                  ->execute();
    }
}