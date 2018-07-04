<?php

namespace Tests\Stub;

use sonrac\SimpleSeed\SimpleSeed;

/**
 * Class Seed
 */
class Seed extends SimpleSeed
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
        return [
            [
                'username' => 'jane',
                'password' => '3q249p5uwe4rjgklerhtg',
            ],
            [
                'username' => 'john',
                'password' => 'asidlkfhsj;ldfjas;df',
            ],
        ];
    }
}
