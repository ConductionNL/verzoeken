<?php

namespace App\Repository;

use App\Entity\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
    * @param Datetime $date The date used to provide a year for the reference
    * @return integer the referenceId that should be used for the next refenceId
    */
    public function getNextReferenceId($organization, $date = null)
    {
    	if(!$date){
    		$date = New \Datetime;	
    	}
    	
    	$result = $this->createQueryBuilder('r')
    	->select('MAX(r.referenceId) AS reference_id')
    	->andWhere(':organisation MEMBER OF r.organizations')
    	->setParameter('organisation', $organization)
    	//->andWhere('YEAR(r.dateCreated) = YEAR(:date)')
    	//->setParameter('date', $date)
    	->getQuery()
    	->getOneOrNullResult()
    	;
    	
    	if(!$result){
    		return 1;
    	}
    	else{
    		return $result['reference_id'] + 1;
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
