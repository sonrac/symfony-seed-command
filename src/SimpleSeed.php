<?php

namespace sonrac\SimpleSeed;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class SimpleSeed
 * Simple seed command runner.
 *
 * @author  Sergii Donii <doniysa@gmail.com>
 */
abstract class SimpleSeed implements SeedInterface
{
    /**
     * {@inheritdoc}
     */
    public function run(QueryBuilder $builder, Connection $connection)
    {
        $data = $this->getData();
        $connection->beginTransaction();
        foreach ($data as $datum) {
            try {
                $connection->insert($this->getTable(), $datum);
            } catch (\Exception $e) {
                $connection->rollBack();
            }
        }

        try {
            $connection->commit();
        } catch (ConnectionException $e) {
            // No active transaction
            echo($e->getMessage().PHP_EOL);
        } catch (\Exception $e) {
            $connection->rollBack();
        }

        return true;
    }

    /**
     * Get table for insert.
     *
     * @return string
     *
     * @author Sergii Donii <doniysa@gmail.com>
     */
    abstract protected function getTable();

    /**
     * Get data for table in format:
     * [
     *      [
     *          'field1' => 'value1',
     *      ],
     *      [
     *          'field2' => 'value2',
     *      ],
     *      ....
     *      [
     *          'fieldN' => 'valueN',
     *      ]
     * ].
     *
     * @return mixed
     *
     * @author Sergii Donii <doniysa@gmail.com>
     */
    abstract protected function getData();
}
