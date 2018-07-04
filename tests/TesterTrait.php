<?php

namespace Tests;

use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;

/**
 * Class TesterTrait
 */
trait TesterTrait
{
    protected function checkCount($count = 0)
    {
        $this->assertEquals(
            $count,
            $this->connection->createQueryBuilder()
                ->select('count(id)')
                ->from('users')
                ->execute()
                ->fetchColumn()
        );
    }

    /**
     * Create table.
     *
     * @throws
     *
     * @author Sergii Donii <doniysa@gmail.com>
     */
    protected function createTable()
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
