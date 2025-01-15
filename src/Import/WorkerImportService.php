<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Import;

use Amp\Parallel\Worker\WorkerPool;
use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\PendingExecution;
use EcomDev\MySQL2JSONL\Progress\WorkerProgressConsumer;
use EcomDev\MySQL2JSONL\ProgressNotifier;
use EcomDev\MySQL2JSONL\TableEntry;

use function Amp\async;

final readonly class WorkerImportService implements ImportService
{
    public function __construct(
        private Configuration $configuration,
        private WorkerPool $workerPool,
        private string $inputDirectory,
        private PendingExecution $pendingExecution,
    ) {
    }

    public function importTable(TableEntry $table, ProgressNotifier $notifier): void
    {
        $this->pendingExecution->waitIfLimited($this->configuration->concurrency);
        $this->pendingExecution->pushFuture(
            async(fn() => $this->processTask($table, $notifier))
        );
    }

    private function processTask(TableEntry $table, ProgressNotifier $progressNotifier): void
    {
        $executor = $this->workerPool->submit(
            WorkerTask::taskImportTable($table, $this->configuration, $this->inputDirectory)
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
