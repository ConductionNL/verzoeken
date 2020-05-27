<?php

namespace App\Repository;

use App\Entity\RequestCase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RequestCase|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestCase|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestCase[]    findAll()
 * @method RequestCase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestCaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestCase::class);
    }

    // /**
    //  * @return OpenCase[] Returns an array of OpenCase objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OpenCase
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
