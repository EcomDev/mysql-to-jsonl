<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Import;

use Amp\Cancellation;
use Amp\Parallel\Worker\Task;
use Amp\Sync\Channel;
use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\Export\BlockingExportTable;
use EcomDev\MySQL2JSONL\Export\BlockingExportTableFactory;
use EcomDev\MySQL2JSONL\Export\JsonExportOutputFactory;
use EcomDev\MySQL2JSONL\Progress\WorkerProgressNotifier;
use EcomDev\MySQL2JSONL\TableEntry;

final class WorkerTask implements Task
{
    private static ?BlockingImportService $importer = null;

    public function __construct(
        private readonly TableEntry $table,
        private readonly Configuration $configuration,
        private readonly string $outputPath
    ) {
    }

    public static function taskImportTable(TableEntry $table, Configuration $configuration, string $inputPath): self
    {
        return new self($table, $configuration, $inputPath);
    }

    public function run(Channel $channel, Cancellation $cancellation): mixed
    {
        $this->importTable($channel);
        return true;
    }

    private function initWorker(Configuration $configuration, string $inputPath): void
    {
        self::$importer = BlockingImportServiceFactory::createFromConfiguration(
            $configuration,
            new JsonImportSourceFactory($inputPath, $configuration)
        )->create();
    }

    private function importTable(Channel $channel): void
    {
        if (!self::$importer) {
            $this->initWorker($this->configuration, $this->outputPath);
        }

        self::$importer->importTable($this->table, new WorkerProgressNotifier($channel, 1));
    }
}
