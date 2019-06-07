<?php
/**
 * Created by PhpStorm.
 *
 * @author Sergii Donii <doniysa@gmail.com>
 */

namespace sonrac\SimpleSeed;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class SimpleSeedWithCheckExists.
 *
 * @author  Sergii Donii <doniysa@gmail.com>
 */
abstract class SimpleSeedWithCheckExists extends SimpleSeed
{
    /**
     * Field name for count aggregation function.
     *
     * @var string
     */
    protected $countField = '*';

    /**
     * Data which was been inserted.
     *
     * @var array
     */
    private $insertedData = [];

    /**
     * Data which was been skipped.
     *
     * @var array
     */
    private $skippedData = [];

    /**
     * {@inheritdoc}
     */
    public function run(QueryBuilder $builder, Connection $connection)
    {
        $this->insertedData = [];
        $this->skippedData  = [];

        $data = $this->getData();

        foreach ($data as $datum) {
            $whereData = $this->getWhereForRow($datum);

            $checkInsertAllow = true;

            if (is_array($whereData) && count($whereData)) {
                $builder = $connection->createQueryBuilder()
                        ->select(['count('.$this->countField.')'])
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
     * Get exists data in table.
     *
     * @param \Doctrine\DBAL\Connection $connection
     *
     * @return array
     */
    protected function getExistsData(Connection $connection)
    {
        $queryBuilder = $connection->createQueryBuilder();
        $checkColumns = $this->getBatchData($this->getData());
        $columnNames  = array_keys($checkColumns);
        $queryBuilder->select($columnNames);

        foreach ($checkColumns as $name => $values) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->in($name, $values)
            );
        }

        return $queryBuilder->execute()->fetchAll();
    }

    /**
     * Get inserted data.
     *
     * @return array
     */
    public function getInsertedData()
    {
        return $this->insertedData;
    }

    /**
     * Get skipped data.
     *
     * @return array
     */
    public function getSkippedData()
    {
        return $this->skippedData;
    }

    /**
     * Get where for check exists data.
     *
     * @param array $data
     *
     * @return array
     *
     * @author Sergii Donii <doniysa@gmail.com>
     */
    abstract protected function getWhereForRow($data);

    public function prepeareWhere($data)
    {
        $where = '';

        foreach ($data as $name => $value) {
            $where .= (strlen($where) ? ' AND ' : '')." `$name` = :$name";
        }

        return $where;
    }

    /**
     * Get batch data for search.
     *
     * @param array $fields
     *
     * @return array
     */
    private function getBatchData($fields)
    {
        $params = [];

        foreach ($fields as $nextColumn) {
            $whereFields = $this->getWhereForRow($nextColumn);

            foreach ($whereFields as $name => $value) {
                if (!isset($params[$name])) {
                    $params[$name] = [];
                }

                $params[$name][] = $value;
            }
        }

        return $params;
    }
}
