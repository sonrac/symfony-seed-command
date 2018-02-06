<?php

namespace sonrac\SimpleSeed;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SeedCommand
 * Seed command.
 *
 * @author  Sergii Donii <s.donii@infomir.com>
 */
class SeedCommand extends Command
{
    /**
     * Database connection.
     *
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * SeedCommand constructor.
     *
     * @param null|string               $name
     * @param \Doctrine\DBAL\Connection $connection
     *
     * @throws \Exception
     */
    public function __construct($name, $connection)
    {
        parent::__construct($name);

        $this->connection = $connection;

        if (empty($this->connection)) {
            throw new \Exception('Connection does not set');
        }
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
     * Check correct seed class.
     *
     * @param string $class
     *
     * @throws InvalidOptionException
     * @throws \Exception
     */
    private function checkSeedClass($class)
    {
        if (empty($class) || !class_exists($class)) {
            throw new SeedClassNotFound();
        }

        $reflection = new \ReflectionClass($class);

        if (!$reflection->implementsInterface(SeedInterface::class)) {
            throw new InvalidSeedClassException();
        }
    }
}
