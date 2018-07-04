<?php
// phpcs:disable

/**
 * Created by PhpStorm.
 *
 * @author Sergii Donii <doniysa@gmail.com>
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
 * @author Sergii Donii <doniysa@gmail.com>
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
// phpcs:enable
