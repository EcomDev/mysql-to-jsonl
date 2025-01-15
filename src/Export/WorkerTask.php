<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

use Amp\Cancellation;
use Amp\Parallel\Worker\Task;
use Amp\Sync\Channel;
use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\Progress\WorkerProgressNotifier;
use EcomDev\MySQL2JSONL\TableEntry;

final class WorkerTask implements Task
{
    private static ?BlockingExportTable $exporter = null;

    public function __construct(
        private readonly TableEntry $table,
        private readonly Configuration $configuration,
        private readonly string $outputPath
    ) {
    }

    public static function taskExportTable(TableEntry $table, Configuration $configuration, string $outputPath): self
    {
        return new self($table, $configuration, $outputPath);
    }

    public function run(Channel $channel, Cancellation $cancellation): mixed
    {
        $this->exportTable($channel);
        return true;
    }

    private function initWorker(Configuration $configuration, string $outputPath): void
    {
        self::$exporter = (new BlockingExportTableFactory(
            $configuration,
            new JsonExportOutputFactory($outputPath)
        ))->create();
    }

    private function exportTable(Channel $channel): void
    {
        if (!self::$exporter) {
            $this->initWorker($this->configuration, $this->outputPath);
        }

        self::$exporter->exportTable($this->table, new WorkerProgressNotifier($channel));
    }
}
