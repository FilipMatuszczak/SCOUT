<?php

namespace App\Repository;

use App\Entity\TechnologyRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TechnologyRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method TechnologyRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method TechnologyRequest[]    findAll()
 * @method TechnologyRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechnologyRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TechnologyRequest::class);
    }

    // /**
    //  * @return TechnologyRequest[] Returns an array of TechnologyRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TechnologyRequest
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
