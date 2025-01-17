<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;

use Amp\Mysql\MysqlConnectionPool;
use EcomDev\MySQL2JSONL\Condition\TableNameCondition;

final class ExportTableFactory
{
    public function __construct(
        private readonly MysqlConnectionPool $connectionPool,
        private readonly ProgressNotifier $progressNotifier,
    ) {
    }

    public function createExport(string $tableName): ExportTable
    {
        return new ExportTable($tableName, $this->connectionPool, $this->progressNotifier);
    }

    public function tablesToExport(Configuration $configuration): array
    {
        $result = $this->connectionPool->execute(
            'SELECT TABLE_NAME, TABLE_ROWS FROM information_schema.tables'
            . ' WHERE TABLE_SCHEMA = SCHEMA() AND TABLE_TYPE = ?',
            ['BASE TABLE']
        );

        $tables = [];
        foreach ($result as $row) {
            if (!$configuration->includeCondition->isSatisfiedBy($row['TABLE_NAME'], $row['TABLE_ROWS'])
                || $configuration->excludeCondition->isSatisfiedBy($row['TABLE_NAME'], $row['TABLE_ROWS'])) {
                continue;
            }
            $tables[] = $row['TABLE_NAME'];
        }
        return $tables;
    }
}
