<?php

namespace App\Repository\Articles;

use App\Entity\Articles\Activity;
use App\Entity\Articles\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginatorInterface;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginatorInterface)
    {
        parent::__construct($registry, Activity::class);
        $this->paginatorInterface = $paginatorInterface;
    }

    /**
     * Trouve les activités publiées, paginées.
     *
     * @param int $page
     * @return PaginationInterface
     */
    public function findPublished(int $page): PaginationInterface
    {
        $query = $this->createQueryBuilder('a')
            ->andWhere('a.state = :state')
            ->setParameter('state', 'active')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery();

        return $this->paginatorInterface->paginate($query, $page, 6);
    }

    /**
     * Trouve les activités publiées par catégorie, paginées.
     *
     * @param Category $category
     * @param int $page
     * @return PaginationInterface
     */
    public function findPublishedByCategory(Category $category, int $page): PaginationInterface
    {
        $query = $this->createQueryBuilder('a')
            ->join('a.categories', 'cat')
            ->andWhere('cat.id = :categoryId')
            ->andWhere('a.state = :state')
            ->setParameter('categoryId', $category->getId())
            ->setParameter('state', 'active')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery();

        return $this->paginatorInterface->paginate($query, $page, 6);
    }
}
