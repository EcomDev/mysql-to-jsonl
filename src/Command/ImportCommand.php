<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Command;

use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\ConfigurationException;
use EcomDev\MySQL2JSONL\Export\TableListService;
use EcomDev\MySQL2JSONL\Import\JsonImportSourceFactory;
use EcomDev\MySQL2JSONL\Import\WorkerImportServiceFactory;
use EcomDev\MySQL2JSONL\Progress\ConsoleProgressNotifier;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Amp\async;
use function Amp\File\filesystem;
use function Amp\Future\awaitAll;

#[AsCommand(name: 'import', description: 'Import data into MySQL from a directory with JSONL files')]
class ImportCommand extends Command
{
    public function configure()
    {
        $this->addOption(
            'config',
            'c',
            InputOption::VALUE_REQUIRED,
            'Configuration file',
            'config.json'
        );

        $this->addOption(
            'concurrency',
            'p',
            InputOption::VALUE_REQUIRED,
            'Number of concurrent workers, by default taken from config file',
        );

        $this->addOption(
            'batch-size',
            'b',
            InputOption::VALUE_REQUIRED,
            'Number of rows to process per batch, by default taken from config file ',
        );

        $this->addArgument(
            'directory',
            InputArgument::OPTIONAL,
            'Directory to import data from',
            './data-dump'
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getOption('config');
        if (!file_exists($file)) {
            /* @var FormatterHelper $formatter*/
            $formatter = $this->getHelper('formatter');
            $output->write($formatter->formatSection('Error', sprintf(
                'Configuration file %s does not exist',
                $file
            ), 'error'));
            return 1;
        }

        try {
            $config = Configuration::fromJSON(file_get_contents($file));
            if ($input->getOption('concurrency')) {
                $config = $config->withConcurrency((int)$input->getOption('concurrency'));
            }
            if ($input->getOption('batch-size')) {
                $config = $config->withBatchSize((int)$input->getOption('batch-size'));
            }
        } catch (ConfigurationException $error) {
            $error->output($output);
            return 1;
        }

        $start = microtime(true);

        $progressNotifier = new ConsoleProgressNotifier($output, 'Importing', 'Imported');
        $importer = WorkerImportServiceFactory::fromConfiguration(
            $config,
            $input->getArgument('directory')
        )->create();

        $inputSource = new JsonImportSourceFactory($input->getArgument('directory'), $config);

        foreach ($inputSource->listTables() as $table) {
            $importer->importTable($table, $progressNotifier);
        }

        $importer->await();

        $timeTaken = Helper::formatTime(microtime(true) - $start, 3);
        $output->writeln(
            "Import finished in {$timeTaken}"
        );
        return 0;
    }
}
