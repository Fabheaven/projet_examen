<?php

namespace App\Repository\Articles;

use App\Entity\Articles\Circuit;
use App\Entity\Articles\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Circuit>
 */
class CircuitRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Circuit::class);
        $this->paginator = $paginator;
    }

    /**
     * Retourne une pagination des circuits disponibles.
     *
     * @param int $page
     * @return PaginationInterface
     */
    public function findPublished(int $page): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->andWhere('c.availability = :availability') // Utilisez 'availability' au lieu de 'isPublished'
            ->setParameter('availability', true) // Les circuits disponibles
            ->orderBy('c.createdAt', 'DESC');

        return $this->paginator->paginate(
            $queryBuilder->getQuery(),
            $page,
            10
        );
    }

    /**
     * Retourne une pagination des circuits disponibles d'une catégorie donnée.
     *
     * @param Category $category
     * @param int $page
     * @return PaginationInterface
     */
    public function findPublishedByCategory(Category $category, int $page): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->andWhere('c.availability = :availability') // Utilisez 'availability' au lieu de 'isPublished'
            ->andWhere(':category MEMBER OF c.categories')
            ->setParameter('availability', true) // Les circuits disponibles
            ->setParameter('category', $category)
            ->orderBy('c.createdAt', 'DESC');

        return $this->paginator->paginate(
            $queryBuilder->getQuery(),
            $page,
            10
        );
    }
}