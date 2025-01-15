<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

use Amp\Parallel\Worker\WorkerPool;
use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\PendingExecution;
use EcomDev\MySQL2JSONL\Progress\WorkerProgressConsumer;
use EcomDev\MySQL2JSONL\ProgressNotifier;
use EcomDev\MySQL2JSONL\TableEntry;

use function Amp\async;

final readonly class WorkerExportService implements ExportService
{
    public function __construct(
        private Configuration $configuration,
        private WorkerPool $workerPool,
        private string $outputDirectory,
        private PendingExecution $pendingExecution,
    ) {
    }

    public function exportTable(TableEntry $table, ProgressNotifier $progressNotifier): void
    {
        $this->pendingExecution->waitIfLimited($this->configuration->concurrency);
        $this->pendingExecution->pushFuture(
            async(fn() => $this->processTask($table, $progressNotifier))
        );
    }

    private function processTask(TableEntry $table, ProgressNotifier $progressNotifier): void
    {
        $executor = $this->workerPool->submit(
            WorkerTask::taskExportTable($table, $this->configuration, $this->outputDirectory)
        );
        $notifier = new WorkerProgressConsumer($progressNotifier);
        $notifier->process($executor->getChannel());
        $this->pendingExecution->pushExecution($executor);
    }

    public function await(): void
    {
        $this->pendingExecution->await();
        $this->workerPool->shutdown();
    }
}
