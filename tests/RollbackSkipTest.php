<?php

namespace Tests;

use Tests\Stub\RollbackSkipSeed;

/**
 * Class RollbackSimpleTest.
 */
class RollbackSkipTest extends SimpleSeedTest
{
    /**
     * Test rolback.
     *
     * @throws \Exception
     */
    public function testRollback()
    {
        $this->testInsert();

        $command = new RollbackSkipSeed();

        $this->checkCount(2);

        $this->assertTrue($command->down($this->connection));

        $this->checkCount(1);
    }
}
