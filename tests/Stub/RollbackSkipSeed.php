<?php

namespace Tests\Stub;

use sonrac\SimpleSeed\RollbackSeedInterface;
use sonrac\SimpleSeed\RollbackTrait;

/**
 * Class RollbackSkipSeed.
 */
class RollbackSkipSeed extends Seed implements RollbackSeedInterface
{
    use RollbackTrait;

    public function checkDeleted($data)
    {
        return $data['username'] !== 'jane';
    }
}
