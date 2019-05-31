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
    public function getTable()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return SimpleSeedExistsTest::getData();
    }

    /**
     * {@inheritdoc}
     */
    public function getWhereForRow($data)
    {
        return ['username' => $data['username']];
    }
}
