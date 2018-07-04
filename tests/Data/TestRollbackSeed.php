<?php
/**
 * Created by PhpStorm.
 * User: sonrac
 * Date: 1/26/18
 * Time: 11:53 AM.
 */

namespace Tests\Data;

use Doctrine\DBAL\Connection;
use sonrac\SimpleSeed\RollbackSeedInterface;
use sonrac\SimpleSeed\RollbackTrait;

class TestRollbackSeed extends TestSeed implements RollbackSeedInterface
{
    use RollbackTrait;

    /**
     * {@inheritdoc}
     */
    public function down(Connection $connection = null)
    {
        return 'will be done';
    }
}
