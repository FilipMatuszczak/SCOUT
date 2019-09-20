<?php

namespace App\Repository;

use App\Entity\AddUserToProjectRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AddUserToProjectRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddUserToProjectRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddUserToProjectRequest[]    findAll()
 * @method AddUserToProjectRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddUserToProjectRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AddUserToProjectRequest::class);
    }
}
