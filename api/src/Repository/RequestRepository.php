<?php

namespace App\Repository;

use App\Entity\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Request|null find($id, $lockMode = null, $lockVersion = null)
 * @method Request|null findOneBy(array $criteria, array $orderBy = null)
 * @method Request[]    findAll()
 * @method Request[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Request::class);
    }

    /**
     * @param Organization $organization The orgniaztion for wich this reference should be unique
     * @param Datetime     $date         The date used to provide a year for the reference
     *
     * @return int the referenceId that should be used for the next refenceId
     */
    public function getLastReferenceId($organization, $date = null)
    {
        //if(!$date){
        $start = new \DateTime('first day of January this year');
        $end = new \DateTime('last day of December this year');
        //}

        $result = $this->createQueryBuilder('r')
            ->select('MAX(r.referenceId) AS reference_id')
            ->andWhere(':organization = r.initialOrganization')
            ->setParameter('organization', $organization)
            ->andWhere('r.dateCreated >= :start')
            ->setParameter('start', $start)
            ->andWhere('r.dateCreated <= :end')
            ->setParameter('end', $end)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$result) {
            return 0;
        } else {
            return $result['reference_id'];
        }
    }

    // /**
    //  * @return Request[] Returns an array of Request objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Request
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
