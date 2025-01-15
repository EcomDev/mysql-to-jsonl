<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\ProgressNotifier;
use EcomDev\MySQL2JSONL\TableEntry;

final class BlockingExportTable implements ExportService
{
    public function __construct(
        private readonly \PDO $connection,
        private readonly ExportOutputFactory $outputFactory
    ) {
    }

    public function exportTable(
        TableEntry       $table,
        ProgressNotifier $progressNotifier
    ): void {
        $output = $this->outputFactory->create($table->name);
        $result = $this->connection->prepare(sprintf('SELECT SQL_NO_CACHE * FROM `%s`', $table->name));
        $result->execute();

        $progressNotifier->start($table->name, $table->rowCount);

        $header = [];
        for ($i = 0; $i < $result->columnCount(); $i++) {
            $header[] = $result->getColumnMeta($i)['name'];
        }
        $output->open($header);

        $index = 0;
        foreach ($result as $row) {
            $output->write($row);
            $progressNotifier->update($table->name, ++$index);
        }

        $output->close();
        $progressNotifier->finish($table->name);
    }
}
