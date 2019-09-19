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

    public function fetchUsersByFilters(
        $from,
        $to,
        $sorting,
        $firstName,
        $lastName,
        $technology,
        $city,
        $language
    )
    {
        $queryBuilder =  $this->createQueryBuilder('u');
            if (!empty($firstName)) {
                $queryBuilder->andWhere('u.firstname like :firstname')
                    ->setParameter('firstname', $firstName);
            }
            if (!empty($lastName)) {
                $queryBuilder->andWhere('u.lastname like :lastname')
                ->setParameter('lastname', $lastName);
            }

            if (isset($technology)) {
                $queryBuilder->andWhere(':technology MEMBER OF u.technology')
                    ->setParameter('technology', $technology);
            }

            if (isset($city)) {
                $queryBuilder->andWhere(':city MEMBER OF u.city')
                    ->setParameter('city', $city);
            }

            if (isset($language)) {
                $queryBuilder->andWhere(':language MEMBER OF u.language')
                    ->setParameter('language', $language);
            }

            $queryBuilder->andWhere('BIT_AND(u.options, 8) = 0')->setFirstResult($from)->setMaxResults($to)->orderBy('u.' . $sorting['sortKey'], $sorting['dir']);

            return $queryBuilder->getQuery()->getResult();
    }

    public function fetchUserIdsForNewsletter()
    {
        $queryBuilder = $this->createQueryBuilder('u');

        return $queryBuilder->select('u.userId')->where('BIT_AND(u.options, 32) = 0')->getQuery()->getResult();
    }
}
