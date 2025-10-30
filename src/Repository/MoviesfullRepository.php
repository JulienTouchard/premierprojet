<?php

namespace App\Repository;

use App\Entity\Moviesfull;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Moviesfull>
 */
class MoviesfullRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Moviesfull::class);
    }
    public function findLike ($value,$field){
        return $this->createQueryBuilder('m')
            ->andWhere('m.'.$field.' LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('m.'.$field, 'ASC')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Moviesfull[] Returns an array of Moviesfull objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Moviesfull
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
