<?php

namespace __namespace__;

use sonrac\SimpleSeed\RollbackTrait;
use sonrac\SimpleSeed\SimpleSeedWithCheckExists;

/**
 * Class __classname__.
 * Auto generated seed.
 */
class __classname__ extends SimpleSeedWithCheckExists
{
    use RollbackTrait;

    /**
     * @inheritDoc
     */
    protected function getTable()
    {
        return "{table_name}";
    }

    /**
     * @inheritDoc
     */
    protected function getData()
    {
        return [__data__];
    }

    /**
     * @inheritDoc
     */
    protected function getWhereForRow($data)
    {
        return [__check_data__];
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
        return [__rollback_data__];
    }
}
