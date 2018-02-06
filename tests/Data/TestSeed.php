<?php
/**
 * Created by PhpStorm.
 * User: sonrac
 * Date: 1/26/18
 * Time: 11:53 AM.
 */

namespace Tests\Data;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use sonrac\SimpleSeed\SeedInterface;

class TestSeed implements SeedInterface
{
    /**
     * {@inheritdoc}
     */
    public function run(QueryBuilder $builder, Connection $connection = null)
    {
        return 'will be running';
    }
}
