<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Import;

use Amp\Parallel\Worker\ContextWorkerPool;
use Amp\Parallel\Worker\WorkerPool;
use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\PendingExecution;

final readonly class WorkerImportServiceFactory implements ImportServiceFactory
{
    private function __construct(
        private Configuration $configuration,
        private WorkerPool    $workerPool,
        private string        $inputDirectory
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

    public function create(): WorkerImportService
    {
        return new WorkerImportService(
            $this->configuration,
            $this->workerPool,
            $this->inputDirectory,
            new PendingExecution(),
            BlockingImportCleanupService::create(
                $this->configuration->importMode,
                $this->configuration->createPDOConnection()
            ),
        );
    }
}
