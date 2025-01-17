<?php

namespace EcomDev\MySQL2JSONL\Command;

use Amp\Pipeline\Queue;
use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\ConfigurationException;
use EcomDev\MySQL2JSONL\ExportTableFactory;
use EcomDev\MySQL2JSONL\Progress\ExportProgressNotifier;
use Revolt\EventLoop;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use function Amp\async;
use function Amp\delay;
use function Amp\Future\awaitAll;

#[AsCommand(name: 'export', description: 'Export data from MySQL to a directory with JSONL files')]
class ExportCommand extends Command
{
    public function configure()
    {
        $this->addOption(
            'config',
            'c', InputOption::VALUE_REQUIRED,
            'Configuration file',
            'config.json'
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
        } catch (ConfigurationException $error) {
            $error->output($output);
            return 1;
        }

        $notifiers = new ExportProgressNotifier($output);
        $futures = [];
        $connectionPool = $config->createConnectionPool();
        $factory = new ExportTableFactory($connectionPool, $notifiers);
        foreach ($factory->tablesToExport($config) as $table) {
            $queue = new Queue(100);

            $futures[] = async(function () use ($factory, $table, $queue) {
                $factory->createExport($table)->run($queue);
            });

            $futures[] = async(function () use ($queue, $output) {
                foreach ($queue->iterate() as $item) {
                }
            });

            if (count($futures) >= 100) {
                awaitAll($futures);
                $futures = [];
            }
        }

        EventLoop::run();
        return 0;
    }
}