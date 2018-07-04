<?php

namespace sonrac\SimpleSeed;

use Doctrine\DBAL\Connection;

/**
 * Interface RollbackSeedInterface
 */
interface RollbackSeedInterface
{
    /**
     * Rollback seed command.
     *
     * @param \Doctrine\DBAL\Connection $connection
     *
     * @return bool
     */
    public function down(Connection $connection);
}
