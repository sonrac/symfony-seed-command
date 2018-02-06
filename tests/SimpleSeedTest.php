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
use sonrac\SimpleSeed\SimpleSeed;

/**
 * Class SimpleSeedTest.
 *
 * @author  Sergii Donii <s.donii@infomir.com>
 */
class SimpleSeedTest extends TestCase
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
        $command = new Seed();
        $this->assertTrue($command->run($this->connection->createQueryBuilder(), $this->connection));

        $this->assertEquals(2, $this->connection->createQueryBuilder()
            ->select('count(id)')->from('users', 'users')->execute()->fetch(\PDO::FETCH_COLUMN));
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
}

class Seed extends SimpleSeed
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
