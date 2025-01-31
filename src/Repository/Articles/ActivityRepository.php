<?php

namespace App\Repository\Articles;

use App\Entity\Articles\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Activity>
 * @return PaginationInterface
 * @param int $page
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginatorInterface)
    {
        parent::__construct($registry, Activity::class);
    }

    
    public function findPublished(int $page): PaginationInterface
    {
        $data = $this->createQueryBuilder('ac')
                ->where('ac.state = :state')  // Recherche les articles actifs
                ->setParameter('state', 'active')  // Le statut que tu veux
                ->orderBy('ac.createdAt', 'DESC')  // Trie par date de création
                ->getQuery();

        $activities = $this->paginatorInterface->paginate($data, $page, 6);

        return $activities;
    }
}

