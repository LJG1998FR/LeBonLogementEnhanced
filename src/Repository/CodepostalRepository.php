<?php

namespace App\Repository;

use App\Entity\Codepostal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Codepostal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Codepostal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Codepostal[]    findAll()
 * @method Codepostal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodepostalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Codepostal::class);
    }

    // /**
    //  * @return Codepostal[] Returns an array of Codepostal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Codepostal
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
