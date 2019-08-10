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

    public function findCreatedUserByUsernameAndChangePasswordLink($username, $changePasswordLink): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.' . User::COLUMN_USERNAME . ' = :username')
            ->andWhere('u.' . User::COLUMN_CHANGE_PASSWORD_LINK . ' = :change_password_link')
            ->andWhere('BIT_AND(u.' . User::COLUMN_OPTIONS . ', ' . User::USER_CHANGING_PASSWORD . ') = ' . User::USER_CHANGING_PASSWORD)
            ->setParameters([
                'username' => $username,
                'change_password_link' => $changePasswordLink,
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function fetchUsersByFilters($from, $to, $sorting = 'ASC', $firstName = null, $lastName = null)
    {
        $queryBuilder =  $this->createQueryBuilder('u');
            if ($firstName) {
                $queryBuilder->andWhere('u.' . User::COLUMN_FIRST_NAME . ' = :firstName')
                    ->setParameter('firstName', $firstName);
            }
            if ($lastName) {
                $queryBuilder->andWhere('u.' . User::COLUMN_LAST_NAME . ' = :lastName')
                ->setParameter('lastName', $lastName);
            }
            $queryBuilder->setFirstResult($from)->setMaxResults($to)->orderBy(User::COLUMN_FIRST_NAME, $sorting);

            return $queryBuilder->getQuery()->getResult();
    }
}
