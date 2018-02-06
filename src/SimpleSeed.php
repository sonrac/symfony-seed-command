<?php

namespace sonrac\SimpleSeed;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class SimpleSeed
 * Simple seed command runner.
 *
 * @author  Sergii Donii <s.donii@infomir.com>
 */
abstract class SimpleSeed implements SeedInterface
{
    /**
     * {@inheritdoc}
     */
    public function run(QueryBuilder $builder, Connection $connection = null)
    {
        $data = $this->getData();

        foreach ($data as $datum) {
            $connection->insert($this->getTable(), $datum);
        }

        return true;
    }

    /**
     * Get table for insert.
     *
     * @return string
     *
     * @author Sergii Donii <s.donii@infomir.com>
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
     * @author Sergii Donii <s.donii@infomir.com>
     */
    abstract protected function getData();
}
