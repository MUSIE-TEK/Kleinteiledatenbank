<?php

namespace App\Repository;

use App\Entity\Toner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Toner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Toner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Toner[]    findAll()
 * @method Toner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TonerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Toner::class);
    }

    public function save(Toner $toner)
    {
        $this->_em->persist($toner);
        $this->_em->flush();
    }

    /**
     * @return Toner[] Returns an array of Toner objects
     */
    public function findByQuery($query): array
    {
        return $this->createQueryBuilder('toner')
                    ->select('toner')
                    ->where('toner.articleNumber LIKE :query')
                    ->orWhere('toner.color LIKE :query')
                    ->orWhere('toner.description LIKE :query')
                    ->orWhere('toner.id LIKE :query')
                    ->orWhere('toner.position LIKE :query')
                    ->setParameter('query', '%' . $query . '%')
                    ->orderBy('toner.id', 'ASC')
                    ->setMaxResults(10)
                    ->getQuery()->getResult();
    }
}
