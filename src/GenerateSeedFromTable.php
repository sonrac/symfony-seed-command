<?php

namespace sonrac\SimpleSeed;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateSeedFromTable.
 */
class GenerateSeedFromTable extends Command
{
    /**
     * Connection object.
     *
     * @var \Doctrine\DBAL\Connection|null
     */
    protected $connection = null;

    /**
     * Primary key.
     *
     * @var \Doctrine\DBAL\Schema\Index|null
     */
    protected $primaryKey;

    /**
     * GenerateSeedFromTable constructor.
     *
     * @param null|string                    $name
     * @param \Doctrine\DBAL\Connection|null $connection
     */
    public function __construct($name = null, Connection $connection = null)
    {
        parent::__construct($name);

        $this->connection = $connection;
    }

    /**
     * Configure.
     */
    protected function configure()
    {
        $this->setName('seed:generate')
             ->addOption(
                 'table',
                 't',
                 InputOption::VALUE_REQUIRED,
                 'Table name for seed generate'
             )
             ->addOption(
                 'classname',
                 'p',
                 InputOption::VALUE_REQUIRED,
                 'Seed class name'
             )
             ->addOption(
                 'rollback',
                 'r',
                 InputOption::VALUE_NONE,
                 'Generate seed with rollback'
             )
             ->addOption(
                 'check-exists',
                 'e',
                 InputOption::VALUE_NONE,
                 'Generate seed with check exists'
             )
             ->addOption(
                 'namespace',
                 'w',
                 InputOption::VALUE_OPTIONAL,
                 'Seed namespace. Example: Simple\\\\Seed'
             )
             ->addOption(
                 'out-path',
                 'o',
                 InputOption::VALUE_REQUIRED,
                 'Output path'
             )
             ->addOption(
                 'start-id',
                 's',
                 InputOption::VALUE_OPTIONAL,
                 'Start id for seed generate.'
             )
             ->addOption(
                 'end-id',
                 'l',
                 InputOption::VALUE_REQUIRED,
                 'End id for seed generate'
             )
             ->addOption(
                 'rollback-columns',
                 'k',
                 InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                 'Rollback columns for check'
             )
             ->addOption(
                 'check-columns',
                 'c',
                 InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                 'Check columns. If set generated seed command with check exists'
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = $input->getOption('table');
        $checkExists = $input->getOption('check-exists');
        $isRollback = $input->getOption('rollback');
        $outPath = realpath($input->getOption('out-path'));
        $namespace = $input->getOption('namespace');
        $className = $input->getOption('classname');
        $startID = $input->getOption('start-id');
        $endID = $input->getOption('end-id');
        $rollbackColumns = $input->getOption('rollback-columns') ?: [];
        $checkColumns = $input->getOption('check-columns') ?: [];

        if (!is_readable($outPath)) {
            throw new \InvalidArgumentException('Path does not readable or does not exists');
        }

        if (!$className) {
            throw new \InvalidArgumentException('Class name does set');
        }

        $tableDefinition = $this->connection->getSchemaManager()
                                            ->createSchema()
                                            ->getTable($table);

        /* @var \Doctrine\DBAL\Schema\Index|null $columns */
        $this->primaryKey = $tableDefinition->getPrimaryKey();

        $rolBackTemplate = '';
        $checkTemplate = '';

        if ($this->primaryKey) {
            $columns = $this->primaryKey->getColumns();
            foreach ($columns as $index => $column) {
                /* @var \Doctrine\DBAL\Schema\Identifier $column */
                if ($isRollback && count($rollbackColumns) == 0) {
                    $rolBackTemplate .= (empty($rolBackTemplate) ? "\n" : '').
                                          "            '{$column}' => \$data['`$column`'],".
                                          ($index === count($columns) - 1 ? '' : "\n");
                    $rollbackColumns[] = $column;
                }
                if ($checkExists && count($checkColumns) == 0) {
                    $checkTemplate .= (empty($checkTemplate) ? "\n" : '').
                                       "            '{$column}' => \$data['`$column`'],".
                                       ($index === count($columns) - 1 ? '' : "\n");
                    $checkColumns[] = $column;
                }
            }
        }

        $rolBackTemplate .= empty($rolBackTemplate) ? '' : "\n        ";
        $checkTemplate .= empty($checkTemplate) ? '' : "\n        ";

        $templateName = $this->getTemplate($checkExists, $isRollback);
        $templateString = file_get_contents($templateName);
        $templateString = str_replace(
            [
                "namespace __namespace__;\n\n",
                '__classname__',
                '{table_name}',
                '__rollback_data__',
                '__check_data__',
                '__data__',
            ],
            [
                $namespace ? "namespace {$namespace};\n\n" : '',
                $className,
                $table,
                $rolBackTemplate,
                $checkTemplate,
                $this->scanData($startID, $endID, $table),
            ],
            $templateString
        );

        file_put_contents($outPath."/{$className}.php", $templateString);

        return 0;
    }

    /**
     * Scan data from table.
     *
     * @param int    $startID
     * @param int    $endID
     * @param string $table
     *
     * @return string
     */
    protected function scanData(
        $startID,
        $endID,
        $table
    ) {
        $where = [];
        if ($startID) {
            $where['start_id'] = $startID;
        }
        if ($endID) {
            $where['end_id'] = $endID;
        }

        $offset = 0;
        $limit = 30;
        $dataTemplate = '';
        while (true) {
            $data = $this->getNextData($table, $limit, $offset, $where);

            if (count($data) === 0) {
                return $dataTemplate.'        ';
            }

            foreach ($data as $index => $datum) {
                $dataTemplate .= (empty($dataTemplate) ? "\n" : '')."            [\n";
                foreach ($datum as $column => $value) {
                    $dataTemplate .= (empty($dataTemplate) ? "\n" : '').
                                     "                '`{$column}`' => ".$this->prepareValue($value, $column, 120)
                                     .",\n";
                }
                $dataTemplate .= (empty($dataTemplate) ? "\n" : '')."            ],\n";
            }
            $offset += $limit;
        }
    }

    /**
     * @param       $tableName
     * @param int   $limit
     * @param int   $offset
     * @param array $where
     *
     * @return array
     */
    protected function getNextData($tableName, $limit = 30, $offset = 0, $where = [])
    {
        $query = $this->connection->createQueryBuilder();

        $query->select(['*'])
              ->from($tableName)
              ->setFirstResult($offset)
              ->setMaxResults($limit);

        if (isset($where['start_id'])) {
            $data = explode(',', $where['start_id']);

            $count = 0;
            foreach ($this->primaryKey->getColumns() as $column) {
                if (isset($data[$count])) {
                    $query->andWhere("$column >= :start_{$count}")
                          ->setParameter(":start_{$count}", $data[$count]);
                }
                $count++;
            }
        }

        if (isset($where['end_id'])) {
            $data = explode(',', $where['end_id']);

            $count = 0;
            foreach ($this->primaryKey->getColumns() as $column) {
                if (isset($data[$count])) {
                    $query->andWhere("$column <= :end_{$count}")
                          ->setParameter(":end_{$count}", $data[$count]);
                }
                $count++;
            }
        }

        return $query->execute()->fetchAll();
    }

    protected function prepareValue($value, $columnName, $maxInLine)
    {
        if (mb_strlen($value) > $maxInLine) {
            $start = 0;
            $result = '';
            $countSymbols = 30 + mb_strlen($columnName) - 1;
            while (true) {
                $length = $maxInLine - $countSymbols;

                if (mb_strlen($value) < $length + $start) {
                    $length = mb_strlen($value) - $start;
                }

                if ($length < 0) {
                    break;
                }

                $nextLine = mb_substr($value, $start, $length);
                $resultNextLine = $this->escape($nextLine);
                $offset = 0;

                if (mb_strlen($resultNextLine) + $countSymbols > $maxInLine) {
                    $offsetSecond = 0;
                    while (mb_strlen($resultNextLine) + $countSymbols > $maxInLine) {
                        $offset = mb_strlen($resultNextLine) - $maxInLine - $countSymbols - $offsetSecond;
                        $resultNextLine = $this->escape(mb_substr($value, $start, $maxInLine + $offset));
                        $offsetSecond++;
                    }
                }
                $result .= (empty($result) ? '' :
                        ".\n".str_repeat(' ', $countSymbols - mb_strlen($columnName)))."\"$resultNextLine\"";
                $start += $offset + $maxInLine;
            }

            return $result;
        }

        return '"'.$this->escape($value).'"';
    }

    /**
     * Escape value.
     *
     * @param string $value
     *
     * @return string|string[]|null
     */
    protected function escape($value)
    {
        return str_replace(
            "\n",
            '\\n',
            str_replace(
                '$',
                '\$',
                addslashes($value)
            )
        );
    }

    /**
     * Get template name for seed.
     *
     * @param bool $checkExists
     * @param bool $isRollback
     *
     * @return string
     */
    protected function getTemplate($checkExists, $isRollback)
    {
        if (!$checkExists && !$isRollback) {
            return __DIR__.'/../templates/SimpleSeed.php';
        }

        if ($checkExists && !$isRollback) {
            return __DIR__.'/../templates/SimpleSeedWithCheckExists.php';
        }

        if ($checkExists && $isRollback) {
            return __DIR__.'/../templates/SimpleSeedWithCheckExistsRollback.php';
        }

        return __DIR__.'/../templates/SimpleSeedRollback.php';
    }
}
