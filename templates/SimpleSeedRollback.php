<?php

namespace __namespace__;

use sonrac\SimpleSeed\SimpleSeed;

/**
 * Class __classname__.
 * Auto generated seed.
 */
class __classname__ extends SimpleSeed
{
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
