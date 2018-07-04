<?php
/**
 * Created by PhpStorm.
 *
 * @author Sergii Donii <doniysa@gmail.com>
 */

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Stub\SeedExists;

/**
 * Class SimpleSeedExistsTest.
 *
 * @author  Sergii Donii <doniysa@gmail.com>
 */
class SimpleSeedExistsTest extends TestCase
{
    use TesterTrait;

    /**
     * Doctrine Connection.
     *
     * @var null|\Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * Test insert data in table.
     *
     * @throws \Exception
     *
     * @author Sergii Donii <doniysa@gmail.com>
     */
    public function testInsert()
    {
        $command = new SeedExists();
        $this->assertTrue($command->run($this->connection->createQueryBuilder(), $this->connection));

        $this->assertEquals(2, $this->connection->createQueryBuilder()
            ->select('count(id)')->from('users', 'users')->execute()->fetch(\PDO::FETCH_COLUMN));

        $this->assertEquals(self::getData(), $command->getInsertedData());
        $this->assertEquals([], $command->getSkippedData());
    }

    /**
     * Test insert data in table.
     *
     * @depends testInsert
     *
     * @throws \Exception
     *
     * @author Sergii Donii <doniysa@gmail.com>
     */
    public function testInsertExists()
    {
        $this->testInsert();
        $this->assertEquals(2, $this->connection->createQueryBuilder()
            ->select('count(id)')->from('users', 'users')->execute()->fetch(\PDO::FETCH_COLUMN));

        $command = new SeedExists();
        $this->assertTrue($command->run($this->connection->createQueryBuilder(), $this->connection));

        $this->checkCount(2);

        $this->assertEquals([], $command->getInsertedData());
        $this->assertEquals(self::getData(), $command->getSkippedData());
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\DBAL\DBALException
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

    /**
     * Get data for seed.
     *
     * @return array
     *
     * @author Sergii Donii <doniysa@gmail.com>
     */
    public static function getData()
    {
        return [
            [
                'username' => 'jane',
                'password' => '3q249p5uwe4rjgklerhtg',
            ],
            [
                'username' => 'john',
                'password' => 'asidlkfhsj;ldfjas;df',
            ],
        ];
    }
}
