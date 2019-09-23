<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function fetchSimilarNamesByPrefix($prefix, $limit)
    {
        return $this->createQueryBuilder('p')
            ->select('p.title')
            ->where('p.title like :prefix' )
            ->setParameter('prefix','%'. $prefix .'%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function fetchProjectsByFilters(
        $from,
        $to,
        $sorting,
        $title,
        $technology,
        $member
    )
    {
        $queryBuilder =  $this->createQueryBuilder('p');

        if (!empty($title)) {
            $queryBuilder->andWhere('p.title like :title')
                ->setParameter('title', $title . '%');
        }

        if (isset($technology)) {
            $queryBuilder->andWhere(':technology MEMBER OF p.technology')
                ->setParameter('technology', $technology);
        }

        if (isset($member)) {
            $queryBuilder->andWhere(':member MEMBER OF p.user')
                ->setParameter('member', $member);
        }

        $queryBuilder->setFirstResult($from)->setMaxResults($to)->orderBy('p.' . $sorting['sortKey'], $sorting['dir']);

        return $queryBuilder->getQuery()->getResult();
    }
}
