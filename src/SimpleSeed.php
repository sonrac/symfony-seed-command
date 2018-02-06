
<?php
/**
 * Created by PhpStorm.
 *
 * @author Sergii Donii <s.donii@infomir.com>
 */

namespace sonrac\SimpleSeed;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;


/**
 * Class SimpleSeed
 *
 * @package sonrac\SimpleSeed
 * @author  Sergii Donii <s.donii@infomir.com>
 */
abstract class SimpleSeed implements SeedInterface
{
    /**
     * Get table for insert
     *
     * @return string
     *
     * @author Sergii Donii <s.donii@infomir.com>
     */
    abstract public function getTable();

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
     * ]
     *
     * @return mixed
     * @author Sergii Donii <s.donii@infomir.com>
     */
    abstract public function getData();

    /**
     * {@inheritdoc}
     */
    public function run(QueryBuilder $builder, Connection $connection = null)
    {
        $data = $this->getData();

        foreach ($data as $datum) {
            $connection->insert($this->getTable(), $datum);
        }
    }
}
