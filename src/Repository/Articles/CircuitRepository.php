<?php

namespace App\Repository\Articles;

use App\Entity\Articles\Circuit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use App\Entity\Articles\Category;

/**
 * @extends ServiceEntityRepository<Circuit>
 * @return PaginationInterface
 * @param int $page
 */
class CircuitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginatorInterface)
    {
        parent::__construct($registry, Circuit::class);
    }


    public function findPublished(int $page): PaginationInterface
    {
        $data = $this->createQueryBuilder('ac')
                ->where('ac.state = :state')  // Recherche les articles actifs
                ->setParameter('state', 'active')  // Le statut que tu veux
                ->orderBy('ac.createdAt', 'DESC')  // Trie par date de création
                ->getQuery();
        
        $circuit = $this->paginatorInterface->paginate($data, $page, 10);

        return $circuit;
    }


    public function findByCategoryWithJoins(Category $category): array
    {
        return $this->createQueryBuilder('ci')
            ->leftJoin('ci.category', 'c')
            ->addSelect('c')
            ->andWhere('ci.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }
}
