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

    /**
     * @param $sorting
     * @param $sort
     * @param $firstResult
     * @param $maxResult
     * @param null $title
     * @param null $author
     *
     * @return mixed
     */
    public function fetchProjectsByFilters($sorting, $sort, $firstResult, $maxResult, $title = null)
    {
        $qb = $this->createQueryBuilder('p');

            if ($title) {
                $qb->andWhere('p.title = %:title%')->setParameter('title', $title);
            }

            $qb->orderBy('p.' . $sorting, $sort)->setFirstResult($firstResult)->setMaxResults($maxResult);

            return $qb->getQuery()->getResult();
    }
}
