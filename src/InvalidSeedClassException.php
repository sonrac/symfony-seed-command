<?php

namespace sonrac\SimpleSeed;

/**
 * Class InvalidSeedClassException.
 *
 * @author  Sergii Donii <doniysa@gmail.com>
 */
class InvalidSeedClassException extends \Exception
{
    protected $message = 'Seed class must be implement sonrac\\SimpleSeed\\SeedInterface';

    protected $code = 'invalid.seed.class.implement';
}
