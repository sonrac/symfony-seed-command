<?php

namespace sonrac\SimpleSeed;

/**
 * Class RollBackSeedWithCheckExists.
 */
abstract class RollBackSeedWithCheckExists extends SimpleSeedWithCheckExists implements RollbackSeedInterface
{
    use RollbackTrait;
}
