<?php

namespace Tests\Stub;

use sonrac\SimpleSeed\RollbackSeedInterface;
use sonrac\SimpleSeed\RollbackTrait;

/**
 * Class RollbackSeed
 */
class RollbackSeed extends Seed implements RollbackSeedInterface
{
    use RollbackTrait;
}
