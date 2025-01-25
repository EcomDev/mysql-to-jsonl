<?php

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\Sql\InsertOnDuplicate;

final readonly class BlockingImportServiceFactory implements ImportServiceFactory
{
    private function __construct(
        private Configuration $configuration,
        private ImportSourceFactory $importSourceFactory,
        private InsertOnDuplicate $insertOnDuplicate
    ) {
    }

    public static function createFromConfiguration(Configuration $configuration, ImportSourceFactory $importSourceFactory): self
    {
        return new self($configuration, $importSourceFactory, new InsertOnDuplicate());
    }

    public function create(): BlockingImportService
    {
        $connection = $this->configuration->createPDOConnection();
        return new BlockingImportService(
            $connection,
            $this->insertOnDuplicate,
            $this->importSourceFactory,
            BlockingImportCleanupService::create(
                $this->configuration->importMode,
                $connection,
            ),
            $this->configuration->batchSize,
        );
    }
}
