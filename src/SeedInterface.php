<?php
/**
 * Created by PhpStorm.
 * User: sonrac
 * Date: 1/25/18
 * Time: 7:49 PM
 */

namespace sonrac\SimpleSeed;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Interface SeedInterface
 * Interface for seed command
 *
 * @package sonrac\SimpleSeed
 * @author  Sergii Donii <s.donii@infomir.com>
 */
interface SeedInterface
{
    /**
     * Run seed command
     *
     * @param \Doctrine\DBAL\Query\QueryBuilder $builder
     * @param \Doctrine\DBAL\Connection         $connection
     *
     * @return mixed
     */
    public function run(QueryBuilder $builder, Connection $connection = null);
}
