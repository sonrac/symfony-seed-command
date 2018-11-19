<?php

namespace sonrac\SimpleSeed;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class SimpleSeed
 * Simple seed command runner.
 *
 * @author  Sergii Donii <doniysa@gmail.com>
 */
abstract class SimpleSeed implements SeedInterface
{
    protected $useTransactions = true;

    /**
     * {@inheritdoc}
     */
    public function run(QueryBuilder $builder, Connection $connection)
    {
        $data = $this->getData();
        if ($this->useTransactions) {
            $connection->beginTransaction();
        }

        foreach ($data as $datum) {
            try {
                $connection->insert($this->getTable(), $datum);
            } catch (\Exception $e) {
                if ($this->useTransactions) {
                    $connection->rollBack();
                }

                throw $e;
            }
        }

        if ($this->useTransactions) {
            try {
                $connection->commit();
            } catch (\Exception $e) {
                $connection->rollBack();
                throw $e;
            }
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
