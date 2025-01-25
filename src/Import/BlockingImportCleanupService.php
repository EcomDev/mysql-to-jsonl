<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\TableEntry;
use PDO;

final readonly class BlockingImportCleanupService implements ImportCleanupService
{
    private function __construct(
        private ImportMode $importMode,
        private PDO $connection,
    ) {
    }

    public static function create(
        ImportMode $importMode,
        PDO $connection
    ): BlockingImportCleanupService {
        return new self($importMode, $connection);
    }

    public function cleanTable(TableEntry $table): void
    {
        if ($this->importMode->isTruncate()) {
            $this->connection->query(
                sprintf('TRUNCATE `%s`', $table->name)
            );
        }
    }
}
