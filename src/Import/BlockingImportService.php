<?php

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\ProgressNotifier;
use EcomDev\MySQL2JSONL\Sql\InsertOnDuplicate;
use EcomDev\MySQL2JSONL\TableEntry;
use PDO;

final readonly class BlockingImportService implements ImportService, ImportCleanupService
{
    public function __construct(
        private PDO $connection,
        private InsertOnDuplicate $insertOnDuplicate,
        private ImportSourceFactory $sourceFactory,
        private BlockingImportCleanupService $cleanupService,
        private int $batchSize
    ) {
    }

    public function importTable(TableEntry $table, ProgressNotifier $notifier): void
    {
        $source = $this->sourceFactory->create($table);

        $columns = $source->header();

        $statementCache = InsertStatementCache::create(
            $this->connection,
            $this->insertOnDuplicate,
            $table->name,
            $columns,
        );

        $params = [];
        $rows = 0;
        $notifier->start($table->name, $table->rowCount);
        $this->connection->beginTransaction();
        foreach ($source->rows() as $row) {
            $rows++;
            array_push($params, ...$row);

            if ($this->batchSize === $rows) {
                $statement = $statementCache->prepareStatement($rows);
                $statement->execute($params);
                $notifier->update($table->name, $rows);
                $rows = 0;
                $params = [];
            }
        }

        if ($rows > 0) {
            $statement = $statementCache->prepareStatement($rows);
            $statement->execute($params);
            $notifier->update($table->name, $rows);
        }

        $this->connection->commit();
        $notifier->finish($table->name);
    }

    public function cleanTable(TableEntry $table): void
    {
        $this->cleanupService->cleanTable($table);
    }
}
