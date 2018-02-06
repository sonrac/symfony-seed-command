<?php
/**
 * Created by PhpStorm.
 * User: sonrac
 * Date: 1/26/18
 * Time: 11:53 AM
 */

namespace Tests\Data;


use sonrac\SimpleSeed\SeedInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class TestSeed implements SeedInterface
{
    /**
     * @inheritDoc
     */
    public function run(QueryBuilder $builder, Connection $connection = null)
    {
        return 'will be running';
    }

}
