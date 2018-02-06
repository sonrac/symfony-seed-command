<?php
/**
 * Created by PhpStorm.
 *
 * @author Sergii Donii <s.donii@infomir.com>
 */
require __DIR__.'/../vendor/autoload.php';

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

/**
 * Get doctrine connection.
 *
 * @throws \Doctrine\DBAL\DBALException
 *
 * @return \Doctrine\DBAL\Connection
 *
 * @author Sergii Donii <s.donii@infomir.com>
 */
function sonrac_getDoctrineConnection()
{
    $config = new Configuration();

    $connectionParams = [
        'driver' => 'pdo_sqlite',
        'path'   => __DIR__.'/out/data.sqlite',
    ];

    return DriverManager::getConnection($connectionParams, $config);
}
