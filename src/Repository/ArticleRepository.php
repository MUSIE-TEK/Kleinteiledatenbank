<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $article)
    {
        //persistiere/halte fest die Änderung
        $this->_em->persist($article);
        $this->_em->flush();
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findByQuery($query): array
    {
        //SQL-Befehl zur Suche
        return $this->createQueryBuilder('article')
                            ->select('article', 'supplier', 'category')
                            ->leftJoin('article.supplier', 'supplier')
                            ->leftJoin('article.category', 'category')
                            ->where('article.name LIKE :query')
                            ->orWhere('article.comment LIKE :query')
                            ->orWhere('article.orderid LIKE :query')
                            ->orWhere('article.imac LIKE :query')
                            ->orWhere('article.compatibilities LIKE :query')
                            ->orWhere('article.inventory LIKE :query')
                            ->orWhere('supplier.name LIKE :query')
                            ->orWhere('category.name LIKE :query')
                            //setzt dynamsich den Parameter :query
                            ->setParameter('query', '%' . $query . '%')
                            ->orderBy('article.id', 'ASC')
                            ->setMaxResults(10)
                            //erstelle eine Query und gib mir das Ergebnis zurück
                            ->getQuery()->getResult();
    }
}

