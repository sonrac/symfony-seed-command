<?php

namespace sonrac\SimpleSeed;

use Doctrine\DBAL\Connection;

/**
 * Class RollbackTrait.
 */
trait RollbackTrait
{
    /**
     * {@inheritdoc}
     */
    public function down(Connection $connection)
    {
        $data = $this->getData();

        $queryBuilder = $connection->createQueryBuilder()
            ->delete($this->getTable());

        foreach ($data as $index => $nextRow) {
            if (!$this->checkDeleted($nextRow)) {
                continue;
            }

            $expressions = [];
            foreach ($nextRow as $column => $value) {
                $columnAlias = str_replace('`', '', $column).'_'.$index;
                $expressions[] = $queryBuilder->expr()->eq($column, ':'.$columnAlias);
                $queryBuilder->setParameter($columnAlias, $value);
                $select[$column] = $column;
            }
            $queryBuilder->orWhere(call_user_func_array([$queryBuilder->expr(), 'andX'], $expressions));
        }

        return $queryBuilder->execute() > 0;
    }

    /**
     * Get delete where data.
     *
     * @param array $data
     *
     * @return array
     */
    public function getDeleteFields($data)
    {
        return $data;
    }

    /**
     * Check data for delete.
     *
     * @param array $data
     *
     * @return bool
     */
    public function checkDeleted($data)
    {
        return true;
    }
}
