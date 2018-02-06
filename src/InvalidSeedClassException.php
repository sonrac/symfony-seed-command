<?php

namespace sonrac\SimpleSeed;

/**
 * Class InvalidSeedClassException.
 *
 * @author  Sergii Donii <s.donii@infomir.com>
 */
class InvalidSeedClassException extends \Exception
{
    protected $message = 'Seed class must be implement Command\\SeedInterface';

    protected $code = 'invalid.seed.class.implement';
}
