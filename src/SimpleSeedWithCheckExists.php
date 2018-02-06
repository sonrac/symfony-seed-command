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
 * Class SimpleSeedWithCheckExists
 *
 * @package sonrac\SimpleSeed
 * @author  Sergii Donii <s.donii@infomir.com>
 */
abstract class SimpleSeedWithCheckExists extends SimpleSeed
{
    /**
     * Field name for count aggregation function
     *
     * @var string
     */
    protected $countField = '*';

    /**
     * Data which was been inserted
     *
     * @var array
     */
    private $insertedData = [];

    /**
     * Data which was been skipped
     *
     * @var array
     */
    private $skippedData = [];

    /**
     * {@inheritdoc}
     */
    public function run(QueryBuilder $builder, Connection $connection = null)
    {
        $this->insertedData = [];
        $this->skippedData = [];

        $data = $this->getData();

        foreach ($data as $datum) {
            $whereData = $this->getWhereForRow($datum);

            $checkInsertAllow = true;

            if (is_array($whereData) && count($whereData)) {
                $builder = $connection->createQueryBuilder()
                        ->select(['count(' . $this->countField . ')'])
                        ->from($this->getTable(), $this->getTable())
                        ->where($this->prepeareWhere($whereData));

                foreach ($whereData as $name => $value) {
                    $builder->setParameter($name, $value);
                }

                $checkInsertAllow = $builder->execute()->fetch(\PDO::FETCH_COLUMN) == 0;
            }

            if ($checkInsertAllow) {
                $connection->insert($this->getTable(), $datum);
                $this->insertedData[] = $datum;
            } else {
                $this->skippedData[] = $datum;
            }
        }

        return true;
    }

    /**
     * Get inserted data
     *
     * @return array
     */
    public function getInsertedData()
    {
        return $this->insertedData;
    }

    /**
     * Get skipped data
     *
     * @return array
     */
    public function getSkippedData()
    {
        return $this->skippedData;
    }

    /**
     * Get where for check exists data
     *
     * @param array $data
     *
     * @return array
     * @author Sergii Donii <s.donii@infomir.com>
     */
    abstract protected function getWhereForRow($data);

    protected function prepeareWhere($data) {
        $where = '';

        foreach ($data as $name => $value) {
            $where .= (strlen($where) ? ' AND ' : '') . " `$name` = :$name";
        }

        return $where;
    }
}
