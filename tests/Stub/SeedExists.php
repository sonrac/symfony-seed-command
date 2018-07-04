<?php

namespace Tests\Stub;

use sonrac\SimpleSeed\SimpleSeedWithCheckExists;
use Tests\SimpleSeedExistsTest;

/**
 * Class SeedExists.
 */
class SeedExists extends SimpleSeedWithCheckExists
{
    /**
     * {@inheritdoc}
     */
    protected function getTable()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData()
    {
        return SimpleSeedExistsTest::getData();
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data)
    {
        return ['username' => $data['username']];
    }
}
