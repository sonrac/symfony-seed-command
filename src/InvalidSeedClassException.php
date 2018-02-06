<?php

namespace sonrac\SimpleSeed;

/**
 * Class InvalidSeedClassException
 *
 * @package sonrac\SimpleSeed
 * @author  Sergii Donii <s.donii@infomir.com>
 */
class InvalidSeedClassException extends \Exception
{
    protected $message = 'Seed class must be implement Command\\SeedInterface';

    protected $code = 'invalid.seed.class.implement';
}
