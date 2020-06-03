<?php

namespace sonrac\SimpleSeed;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SeedCommand
 * Seed command.
 *
 * @author  Sergii Donii <doniysa@gmail.com>
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
    public function __construct($name = null, Connection $connection = null)
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
            ->addOption(
                'class',
                'c',
                InputOption::VALUE_REQUIRED,
                'Seed class name'
            )->addOption(
                'rollback',
                'r',
                InputOption::VALUE_OPTIONAL,
                'Rollback seed',
                false
            );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \sonrac\SimpleSeed\InvalidSeedClassException                if seed class is invalid
     * @throws \Symfony\Component\Console\Exception\InvalidOptionException
     * @throws \Exception
     * @throws \sonrac\SimpleSeed\SeedClassNotFoundException
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $class = $input->getOption('class');
        $rollbackOption = $input->getOption('rollback');
        $isRollback = false !== $rollbackOption;
        $this->checkSeedClass($class, $isRollback);

        /** @var \sonrac\SimpleSeed\SeedInterface|\Tests\Stub\RollbackSeed $instance */
        $instance = new $class($this->connection);

        if ($isRollback) {
            return $instance->down($this->connection);
        }

        return $instance->run($this->connection->createQueryBuilder(), $this->connection) ? 0 : 127;
    }

    /**
     * Check correct seed class.
     *
     * @param string $class
     * @param bool   $checkRollback
     *
     * @throws \Symfony\Component\Console\Exception\InvalidOptionException
     * @throws \Exception
     * @throws \sonrac\SimpleSeed\SeedClassNotFoundException
     * @throws \ReflectionException
     * @throws \sonrac\SimpleSeed\InvalidSeedClassException
     */
    private function checkSeedClass($class, $checkRollback = false)
    {
        if (empty($class) || !class_exists($class)) {
            throw new SeedClassNotFoundException();
        }

        $reflection = new \ReflectionClass($class);

        if (!$reflection->implementsInterface('sonrac\SimpleSeed\SeedInterface')) {
            throw new InvalidSeedClassException();
        }

        if ($checkRollback && !$reflection->implementsInterface(RollbackSeedInterface::class)) {
            throw new InvalidSeedClassException('Seed class must be implement sonrac\\SimpleSeed\\RollbackInterface');
        }
    }
}
