<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function save(Category $category): void
    {
        $this->_em->persist($category);
        $this->_em->flush();
    }

    /**
     * @return Category[] Returns an array of Category objects
     */
    public function findByQuery($query): array
    {
        return $this->createQueryBuilder('category')
                    ->select('category')
                    ->where('category.name LIKE :query')
                    ->orWhere('category.shortcut LIKE :query')
                    ->setParameter('query', '%' . $query . '%')
                    ->orderBy('category.id', 'ASC')
                    ->setMaxResults(10)
                    ->getQuery()->getResult();
    }
}
