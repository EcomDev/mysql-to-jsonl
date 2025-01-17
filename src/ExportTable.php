<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;

use Amp\Future;
use Amp\Mysql\MysqlConnectionPool;
use Amp\Pipeline\Queue;
use function Amp\async;
use function Amp\delay;

final class ExportTable
{
    public function __construct(
        private readonly string $tableName,
        private readonly MysqlConnectionPool $connectionPool,
        private readonly ProgressNotifier $progressNotifier,
    ) {
    }

    public function run(Queue $queue): void
    {
        $total = $this->connectionPool
            ->execute(sprintf('SELECT COUNT(*) as `total` FROM `%s`', $this->tableName))
            ->fetchRow();

        $this->progressNotifier->start($this->tableName, $total['total']);

        $result = $this->connectionPool->execute(sprintf('SELECT * FROM `%s`', $this->tableName));
        $header = [];
        foreach ($result->getColumnDefinitions() as $column) {
            $header[] = $column->getName();
        }
        $queue->push($header);

        $index = 0;
        foreach ($result as $row) {
            $queue->push(array_values($row));
            $this->progressNotifier->update($this->tableName, ++$index);
        }

        $queue->complete();
        $this->progressNotifier->finish($this->tableName);
    }
}
