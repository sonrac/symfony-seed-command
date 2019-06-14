<?php

namespace Tests;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\MySqlSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Statement;
use PHPUnit\Framework\TestCase;
use sonrac\SimpleSeed\GenerateSeedFromTable;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class GenerateSeedFromTableTest extends TestCase
{
    public function getConnectionMock($expectFetch = true)
    {
        $configuration = $this->getMockBuilder(Configuration::class)->getMock();
        $driver        = $this->getMockBuilder(Driver::class)->getMock();
        $connection    = $this->getMockBuilder("Doctrine\DBAL\Connection")
                              ->setConstructorArgs(
                                  [
                                      [],
                                      $driver,
                                      $configuration,
                                  ]
                              )->disableOriginalConstructor()
                              ->getMock();

        $schemaManagerMock = $this->getMockBuilder(MySqlSchemaManager::class)
                                  ->disableOriginalConstructor()
                                  ->setMethods(['createSchema'])
                                  ->getMock();

        $connection->expects($this->any())
                   ->method('getSchemaManager')
                   ->willReturn($schemaManagerMock);

        $schemaMock = $this->getMockBuilder(Schema::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['getTable'])
                           ->getMock();
        $table      = $this->getMockBuilder(Table::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['getPrimaryKey'])
                           ->getMock();

        $index = $this->getMockBuilder(Index::class)
                      ->disableOriginalConstructor()
                      ->setMethods(['getColumns'])
                      ->getMock();

        $index->expects($this->any())
              ->method('getColumns')
              ->willReturn(['id']);

        $table->expects($this->any())
              ->method('getPrimaryKey')
              ->willReturn($index);

        $schemaMock->expects($this->any())
                   ->method('getTable')
                   ->willReturn($table);

        $schemaManagerMock->expects($this->any())
                          ->method('createSchema')
                          ->willReturn($schemaMock);

        $methods      = [
            'setFirstResult',
            'setMaxResults',
            'from',
            'select',
            'andWhere',
            'setParameter',
        ];
        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
                             ->disableOriginalConstructor()
                             ->setMethods(array_merge($methods, ['execute']))
                             ->getMock();

        foreach ($methods as $method) {
            $queryBuilder->expects($this->any())
                         ->method($method)
                         ->willReturn($queryBuilder);
        }

        $statement = $this->getMockBuilder(Statement::class)
                          ->disableOriginalConstructor()
                          ->setMethods(['fetchAll'])
                          ->getMock();

        if ($expectFetch) {
            $statement->expects($this->at(0))
                      ->method('fetchAll')
                      ->willReturn([
                          [
                              'id'       => 1,
                              'username' => 'sonrac',
                              'password' => 'sonrac_password',
                          ],
                      ]);

            $statement->expects($this->atLeast(1))
                      ->method('fetchAll')
                      ->willReturn([]);
        }

        $statementEmpty = $this->getMockBuilder(Statement::class)
                          ->disableOriginalConstructor()
                          ->setMethods(['fetchAll'])
                          ->getMock();

        $statementEmpty->expects($this->any())
                  ->method('fetchAll')
                  ->willReturn([]);

        $queryBuilder->expects($this->any())
                     ->method('execute')
                     ->willReturn($statement);

        $connection->expects($this->any())
                   ->method('createQueryBuilder')
                   ->willReturn($queryBuilder);

        return $connection;
    }

    protected function getCommand($expectFetch = true)
    {
        return new GenerateSeedFromTable(null, $this->getConnectionMock($expectFetch));
    }

    protected function getInputInterface(
        $table,
        $checkExists,
        $isRollback,
        $outPath,
        $namespace,
        $className,
        $startID,
        $endID,
        $rollbackColumns,
        $checkColumns
    ) {
        $input = $this->getMockBuilder(ArrayInput::class)
                      ->disableOriginalConstructor()
                      ->setMethods(['getOption'])
                      ->getMock();

        foreach (func_get_args() as $index => $arg) {
            $input->expects($this->at($index))
                  ->method('getOption')
                  ->willReturn($arg);
        }

        return $input;
    }

    public function testGenerateSimple()
    {
        $command = $this->getCommand();

        $input  = $this->getInputInterface(
            'test',
            false,
            false,
            __DIR__.'/out',
            'Tests\\Seed',
            'SeedClass',
            null,
            null,
            null,
            null
        );
        $method = new \ReflectionMethod($command, 'execute');
        $method->setAccessible(true);
        $method->invoke($command, $input, new ConsoleOutput());

        $this->assertFileExists(__DIR__.'/out/SeedClass.php');
        $content = file_get_contents(__DIR__.'/out/SeedClass.php');

        $this->assertContains('return "test";', $content);
        $str = <<<EOL
        return [
            [
                '`id`' => "1",
                '`username`' => "sonrac",
                '`password`' => "sonrac_password",
            ],
        ];
EOL;

        $this->assertContains($str, $content);
    }

    public function testPathNotReadableException()
    {
        $command = $this->getCommand(false);

        $input  = $this->getInputInterface(
            'test',
            false,
            false,
            '/1/2/3/4/5/6/6/7/78/',
            'Tests\\Seed',
            'SeedClass',
            null,
            null,
            null,
            null
        );
        $method = new \ReflectionMethod($command, 'execute');
        $method->setAccessible(true);
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException(\InvalidArgumentException::class);
        } else {
            $this->expectException(\InvalidArgumentException::class);
        }
        $method->invoke($command, $input, new ConsoleOutput());
    }

    public function testEmptyClassnameException()
    {
        $command = $this->getCommand(false);

        $input  = $this->getInputInterface(
            'test',
            false,
            false,
            __DIR__.'/out',
            'Tests\\Seed',
            '',
            null,
            null,
            null,
            null
        );
        $method = new \ReflectionMethod($command, 'execute');
        $method->setAccessible(true);
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException(\InvalidArgumentException::class);
        } else {
            $this->expectException(\InvalidArgumentException::class);
        }
        $method->invoke($command, $input, new ConsoleOutput());
    }
}
