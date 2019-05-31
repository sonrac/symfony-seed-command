<?php

namespace __namespace__;

use sonrac\SimpleSeed\RollbackTrait;
use sonrac\SimpleSeed\SimpleSeed;

/**
 * Class __classname__.
 * Auto generated seed.
 */
class __classname__ extends SimpleSeed
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
}
