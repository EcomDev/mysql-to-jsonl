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

    public function create(): ImportService
    {
        return new BlockingImportService(
            $this->configuration->createPDOConnection(),
            $this->insertOnDuplicate,
            $this->importSourceFactory,
            $this->configuration->importMode,
            $this->configuration->batchSize
        );
    }
}
