<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;

use Amp\Mysql\MysqlConnectionPool;

class ExportTable
{
    public function __construct(
        private readonly string $tableName,
        private readonly MysqlConnectionPool $connectionPool,
        private readonly ProgressNotifier $progressNotifier,
    ) {

    }
}