<?php

declare(strict_types=1);

namespace SimplePhpCrud\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Query;
use SimplePhpCrud\Entity\Client;

/**
 * Class ClientRepository
 */
class ClientRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * ClientRepository constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \SimplePhpCrud\Entity\Client $Client
     */
    public function persist(Client $Client): void
    {
        $this->entityManager->merge($Client);
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
        $qb->select('c.cpf,c.email,c.fullName,c.phone')
           ->from(Client::class, 'c')
           ->addCriteria($criteria);

        return $qb->getQuery()
                  ->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @param \Doctrine\Common\Collections\Criteria $criteria
     *
     * @param \SimplePhpCrud\Entity\Client         $Client
     *
     * @return int
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function deleteBy(Criteria $criteria, Client $Client): int
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->delete(Client::class, 'Client')
           ->addCriteria($criteria);

        $queryResult = $qb->getQuery()
                          ->execute();
        if ($queryResult) {
            $eventArgs = new LifecycleEventArgs($Client, $this->entityManager);
            $this->entityManager->getEventManager()
                                ->dispatchEvent(Events::postRemove, $eventArgs);
        }

        return $queryResult;
    }
}
