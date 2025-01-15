<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\TableEntry;

final readonly class TableListService
{
    public function __construct(private Configuration $configuration)
    {
    }

    public function tablesToExport(): array
    {
        $connection = $this->configuration->createPDOConnection();
        $connection->prepare('SET information_schema_stats_expiry=0')->execute();

        $result = $connection->prepare(
            'SELECT TABLE_NAME, TABLE_ROWS FROM information_schema.tables'
            . ' WHERE TABLE_SCHEMA = SCHEMA() AND TABLE_TYPE = ?'
        );
        $result->execute(['BASE TABLE']);

        $tables = [];
        foreach ($result as [$tableName, $rowCount]) {
            $tableMetadata = new TableEntry($tableName, $rowCount);
            $isSkipped = !$this->configuration->includeCondition->isSatisfiedBy($tableMetadata)
                || $this->configuration->excludeCondition->isSatisfiedBy($tableMetadata);
            if ($isSkipped) {
                continue;
            }
            $tables[] = $tableMetadata;
        }
        return $tables;
    }
}
