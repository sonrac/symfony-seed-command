<?php

namespace sonrac\SimpleSeed;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Interface SeedInterface
 * Interface for seed command.
 *
 * @author  Sergii Donii <doniysa@gmail.com>
 */
interface SeedInterface
{
    /**
     * Run seed command.
     *
     * @param \Doctrine\DBAL\Query\QueryBuilder $builder
     * @param \Doctrine\DBAL\Connection         $connection
     *
     * @return mixed
     */
    public function run(QueryBuilder $builder, Connection $connection = null);
}
