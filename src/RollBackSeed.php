<?php

namespace sonrac\SimpleSeed;

/**
 * Class RollBackSeed
 */
abstract class RollBackSeed extends SimpleSeed implements RollbackSeedInterface
{
    use RollbackTrait;
}
