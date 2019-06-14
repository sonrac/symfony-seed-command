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
        $this->skippedData = [];

        $data = $this->getData();
        $existsData = $this->getExistsData($connection);

        foreach ($data as $datum) {
            if (!$this->findExisted($datum, $existsData)) {
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
     * @param array                     $selectFields
     *
     * @return array
     */
    protected function getExistsData(Connection $connection, $selectFields = [])
    {
        $queryBuilder = $connection->createQueryBuilder();

        $data = $this->getData();

        if (!count($data)) {
            return [];
        }

        $selectFields = count($selectFields) ? $selectFields : array_keys($this->getWhereForRow(current($data)));
        $queryBuilder->select($selectFields)
                     ->from($this->getTable());

        $select = [];
        foreach ($data as $index => $nextRow) {
            $expressions = [];
            foreach ($selectFields as $column) {
                $columnAlias = str_replace('`', '', $column).'_'.$index;
                $expressions[] = $queryBuilder->expr()->eq($column, ':'.$columnAlias);
                $queryBuilder->setParameter($columnAlias, $nextRow[$column]);
                $select[$column] = $column;
            }
            $queryBuilder->orWhere(call_user_func_array([$queryBuilder->expr(), 'andX'], $expressions));
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

    /**
     * Find existed record.
     *
     * @param array $row
     * @param array $exists
     *
     * @return bool
     */
    protected function findExisted($row, $exists)
    {
        foreach ($exists as $nextItem) {
            $result = true;
            foreach ($row as $column => $value) {
                if (!isset($nextItem[$column])) {
                    break;
                }

                $result = $result && $nextItem[$column] == $value;
            }

            if ($result) {
                return true;
            }
        }

        return false;
    }
}
