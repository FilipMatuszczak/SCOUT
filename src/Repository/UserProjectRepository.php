<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class UserProjectRepository
{
    const TABLE = 'users_projects';

    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function setUserAsAuthor($userId, $projectId)
    {
        $qb = new QueryBuilder($this->connection);

        $qb->update(self::TABLE, '')
            ->set('options', 1)
            ->where('user_id = :userId')
            ->andWhere('project_id = :projectId')
            ->setParameters(
                [
                    'userId' => $userId,
                    'projectId' => $projectId,
                ]
            )
            ->execute();
    }
}
