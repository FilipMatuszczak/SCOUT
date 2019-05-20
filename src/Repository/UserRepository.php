<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $username
     * @param $authenticationLink
     *
     * @return User|null
     */
    public function findCreatedUserByUsernameAndAuthenticationLink($username, $authenticationLink): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.' . User::COLUMN_USERNAME . ' = :username')
            ->andWhere('u.' . User::COLUMN_AUTHENTICATION_LINK . ' = :authentication_link')
            ->andWhere('u.' . User::COLUMN_OPTIONS . ' = ' . User::USER_CREATED)
            ->setParameters([
                'username' => $username,
                'authentication_link' => $authenticationLink,
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
