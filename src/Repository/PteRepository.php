<?php

namespace App\Repository;

use App\Entity\Pte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Pte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pte[]    findAll()
 * @method Pte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pte::class);
    }

    // /**
    //  * @return Pte[] Returns an array of Pte objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pte
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
