<?php
/**
 * Created by PhpStorm.
 *
 * @author Sergii Donii <doniysa@gmail.com>
 */

namespace sonrac\SimpleSeed;

/**
 * Class SeedClassNotFoundException.
 *
 * @author  Sergii Donii <doniysa@gmail.com>
 */
class SeedClassNotFoundException extends \Exception
{
    /**
     * {@inheritdoc}
     */
    protected $message = 'Seed class not found';

    /**
     * {@inheritdoc}
     */
    protected $code = 'seed.class.not_found';
}
