<?php

namespace App\Repository\Articles;

use App\Entity\Articles\Circuit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Circuit>
 */
class CircuitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Circuit::class);
    }

    public function findPublished(): array
    {
        return $this->createQueryBuilder('cc')
            ->where('cc.state LIKE :state') // Ici, 'cc' fait référence à l'alias de l'entité Activity
            ->setParameter('state', '%STATE_PUBLISHED%') // Fixe le paramètre pour l'état
            ->orderBy('cc.createdAt', 'DESC') // Assure-toi que 'createdAt' existe dans l'entité Activity
            ->getQuery()
            ->getResult();
    }

 
}
