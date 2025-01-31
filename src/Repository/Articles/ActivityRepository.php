<?php

namespace App\Repository\Articles;

use App\Entity\Articles\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function findPublished(): array
    {
        return $this->createQueryBuilder('ac')
            ->where('ac.state LIKE :state') // Ici, 'ac' fait référence à l'alias de l'entité Activity
            ->setParameter('state', '%STATE_PUBLISHED%') // Fixe le paramètre pour l'état
            ->orderBy('ac.createdAt', 'DESC') // Assure-toi que 'createdAt' existe dans l'entité Activity
            ->getQuery()
            ->getResult();
    }
}
