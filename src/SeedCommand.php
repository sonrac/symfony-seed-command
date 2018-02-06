<?php
/**
 * Created by PhpStorm.
 * User: sonrac
 * Date: 1/25/18
 * Time: 7:44 PM
 */

namespace sonrac\SimpleSeed;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SeedCommand extends Command
{
    /**
     * Database connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection = null;

    /**
     * SeedCommand constructor.
     *
     * @param null              $name
     * @param Silex\Application $app
     */
    public function __construct($name = null, $app)
    {
        parent::__construct($name);

        $this->connection = $app['db'];
    }

    /** {@inheritdoc} */
    protected function configure()
    {
        $this->setName('seed:run')
            ->addOption('class', 'c', InputOption::VALUE_REQUIRED);
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->checkSeedClass($class = $input->getOption('class'));

        /** @var SeedInterface $instance */
        $instance = new $class($this->connection);

        return $instance->run($this->connection->createQueryBuilder(), $this->connection);
    }

    /**
     * Check correct seed class
     *
     * @param string $class
     *
     * @throws InvalidOptionException
     * @throws \Exception
     */
    private function checkSeedClass($class)
    {
        if (empty($class) || !class_exists($class)) {
            throw new InvalidOptionException('Seed class does not exists');
        }

        $reflection = new \ReflectionClass($class);

        if (!$reflection->implementsInterface(SeedInterface::class)) {
            throw new \Exception('Seed class must be implement Command\SeedInterface');
        }

    }
}
