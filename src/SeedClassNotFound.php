<?php
/**
 * Created by PhpStorm.
 *
 * @author Sergii Donii <s.donii@infomir.com>
 */

namespace sonrac\SimpleSeed;


/**
 * Class SeedClassNotFound
 *
 * @package sonrac\SimpleSeed
 * @author  Sergii Donii <s.donii@infomir.com>
 */
class SeedClassNotFound extends \Exception
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
