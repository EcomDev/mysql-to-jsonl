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
use EcomDev\MySQL2JSONL\Export\WorkerExportServiceFactory;
use EcomDev\MySQL2JSONL\Progress\ConsoleProgressNotifier;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'export', description: 'Export data from MySQL to a directory with JSONL files')]
class ExportCommand extends Command
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

        $this->addArgument(
            'directory',
            InputArgument::OPTIONAL,
            'Directory to export data to',
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
        } catch (ConfigurationException $error) {
            $error->output($output);
            return 1;
        }

        $start = microtime(true);
        $progressNotifier = new ConsoleProgressNotifier($output, 'Exporting', 'Exported');
        $exporter = WorkerExportServiceFactory::fromConfiguration(
            $config,
            $input->getArgument('directory')
        )->create();
        $tables = (new TableListService($config))->tablesToExport();

        foreach ($tables as $table) {
            $exporter->exportTable($table, $progressNotifier);
        }

        $exporter->await();
        $timeTaken = Helper::formatTime(microtime(true) - $start, 3);
        $output->writeln(
            "Export finished in {$timeTaken}"
        );
        return 0;
    }
}
