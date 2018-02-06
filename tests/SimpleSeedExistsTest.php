<?php
/**
 * Created by PhpStorm.
 *
 * @author Sergii Donii <s.donii@infomir.com>
 */

namespace Tests;

use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use sonrac\SimpleSeed\SimpleSeedWithCheckExists;

/**
 * Class SimpleSeedExistsTest.
 *
 * @author  Sergii Donii <s.donii@infomir.com>
 */
class SimpleSeedExistsTest extends TestCase
{
    /**
     * Doctrine Connection.
     *
     * @var null|\Doctrine\DBAL\Connection
     */
    private $connection = null;

    /**
     * Test insert data in table.
     *
     * @throws \Exception
     *
     * @author Sergii Donii <s.donii@infomir.com>
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
     * @author Sergii Donii <s.donii@infomir.com>
     */
    public function testInsertExists()
    {
        $this->testInsert();
        $this->assertEquals(2, $this->connection->createQueryBuilder()
            ->select('count(id)')->from('users', 'users')->execute()->fetch(\PDO::FETCH_COLUMN));

        $command = new SeedExists();
        $this->assertTrue($command->run($this->connection->createQueryBuilder(), $this->connection));

        $this->assertEquals(2, $this->connection->createQueryBuilder()
            ->select('count(id)')->from('users', 'users')->execute()->fetch(\PDO::FETCH_COLUMN));

        $this->assertEquals([], $command->getInsertedData());
        $this->assertEquals(self::getData(), $command->getSkippedData());
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
     * @author Sergii Donii <s.donii@infomir.com>
     */
    private function checkTableExists()
    {
        return $this->connection->getSchemaManager()->tablesExist(['users']);
    }

    /**
     * Create table.
     *
     * @throws
     *
     * @author Sergii Donii <s.donii@infomir.com>
     */
    private function createTable()
    {
        $table = new Table('users');
        $table->addColumn('id', Type::INTEGER);
        $table->addColumn('username', Type::STRING)
            ->setLength(255)
            ->setNotnull(true);
        $table->addColumn('password', Type::STRING)
            ->setLength(1024)
            ->setNotnull(true);
        $table->setPrimaryKey(['id']);
        $this->connection->getSchemaManager()->createTable($table);
    }

    /**
     * Get data for seed.
     *
     * @return array
     *
     * @author Sergii Donii <s.donii@infomir.com>
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

class SeedExists extends SimpleSeedWithCheckExists
{
    /**
     * {@inheritdoc}
     */
    protected function getTable()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData()
    {
        return SimpleSeedExistsTest::getData();
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data)
    {
        return ['username' => $data['username']];
    }
}
