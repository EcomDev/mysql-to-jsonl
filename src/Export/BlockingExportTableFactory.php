<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

use EcomDev\MySQL2JSONL\Configuration;
use PDO;

final class BlockingExportTableFactory implements ExportServiceFactory
{
    private ?PDO $connection = null;

    public function __construct(
        private readonly Configuration $configuration,
        private readonly ExportOutputFactory $outputFactory
    ) {
    }

    public function create(): BlockingExportTable
    {
        return new BlockingExportTable($this->configuration->createPDOConnection(), $this->outputFactory);
    }
}
