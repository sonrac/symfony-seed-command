<?php

namespace Tests;

use Tests\Stub\RollbackSeed;

/**
 * Class RollbackSimpleTest.
 */
class RollbackSimpleTest extends SimpleSeedTest
{
    /**
     * Test rolback.
     *
     * @throws \Exception
     */
    public function testRollback()
    {
        $this->testInsert();

        $command = new RollbackSeed();

        $this->checkCount(2);

        $this->assertTrue($command->down($this->connection));

        $this->checkCount();
    }
}
