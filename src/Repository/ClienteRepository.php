<?php

declare(strict_types=1);

namespace SimplePhpCrud\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use SimplePhpCrud\Entity\Cliente;

/**
 * Class ClienteRepository
 */
class ClienteRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * ClienteRepository constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \SimplePhpCrud\Entity\Cliente $cliente
     */
    public function persist(Cliente $cliente): void
    {
        $this->entityManager->merge($cliente);
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
        $qb->select('c.cpf,c.email,c.nomeCompleto,c.telefone')
           ->from(Cliente::class, 'c')
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
        $qb->delete(Cliente::class, 'cliente')
           ->addCriteria($criteria);

        return $qb->getQuery()
                  ->execute();
    }
}