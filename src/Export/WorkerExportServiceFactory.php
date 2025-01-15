<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

use Amp\Parallel\Worker\ContextWorkerPool;
use Amp\Parallel\Worker\WorkerPool;
use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\PendingExecution;

final readonly class WorkerExportServiceFactory implements ExportServiceFactory
{
    private function __construct(
        private Configuration $configuration,
        private WorkerPool $workerPool,
        private string $outputDirectory,
    ) {
    }

    public static function fromConfiguration(Configuration $configuration, string $outputDirectory): self
    {
        return new self(
            $configuration,
            new ContextWorkerPool($configuration->concurrency),
            $outputDirectory,
        );
    }

    public function create(): WorkerExportService
    {
        return new WorkerExportService($this->configuration, $this->workerPool, $this->outputDirectory, new PendingExecution());
    }
}
