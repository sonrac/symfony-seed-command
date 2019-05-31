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

        $count = 0;

        foreach ($data as $datum) {
            if (!$this->checkDeleted($datum)) {
                continue;
            }

            $query = $connection->createQueryBuilder()
                ->delete($this->getTable());

            $deleteData = $this->getDeleteFields($datum);
            foreach ($deleteData as $name => $value) {
                $query->andWhere("{$name} = :${name}")
                    ->setParameter($name, $value);
            }

            $query->execute();

            $count++;
        }

        return $count > 0;
    }

    /**
     * Get delete where data.
     *
     * @param array $data
     *
     * @return array
     */
    protected function getDeleteFields($data)
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
    protected function checkDeleted($data)
    {
        return true;
    }
}
