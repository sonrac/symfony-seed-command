<?php
/**
 * Created by PhpStorm.
 *
 * @author Sergii Donii <doniysa@gmail.com>
 */

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Stub\Seed;

/**
 * Class SimpleSeedTest.
 *
 * @author  Sergii Donii <doniysa@gmail.com>
 */
class SimpleSeedTest extends TestCase
{
    use TesterTrait;

    /**
     * Doctrine Connection.
     *
     * @var null|\Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * Test insert data in table.
     *
     * @throws \Exception
     *
     * @author Sergii Donii <doniysa@gmail.com>
     */
    public function testInsert()
    {
        $command = new Seed();
        $this->assertTrue($command->run($this->connection->createQueryBuilder(), $this->connection));

        $this->checkCount(2);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->connection = sonrac_getDoctrineConnection();

        if (!$this->checkTableExists()) {
            $this->createTable();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->connection->getSchemaManager()->dropTable('users');
    }

    /**
     * Check table exists.
     *
     * @author Sergii Donii <doniysa@gmail.com>
     */
    private function checkTableExists()
    {
        return $this->connection->getSchemaManager()->tablesExist(['users']);
    }
}
