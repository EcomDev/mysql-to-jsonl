<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\TableEntry;

final readonly class FakeImportSource implements ImportSource, ImportSourceFactory
{
    public function __construct(
        public TableEntry $table,
        private array $header,
        private array $rows
    ) {
    }

    public function withHeader(array $header): FakeImportSource
    {
        return new self($this->table, $header, $this->rows);
    }

    public function withRow(array $row): FakeImportSource
    {
        $rows = $this->rows;
        $rows[] = $row;

        return new self($this->table->withRows(count($rows)), $this->header, $rows);
    }

    public static function fromTableName(string $tableName): FakeImportSource
    {
        return new self(TableEntry::fromName($tableName), [], []);
    }

    public function header(): array
    {
        return $this->header;
    }

    public function rows(): iterable
    {
        return $this->rows;
    }

    public function create(TableEntry $table): FakeImportSource
    {
        if ($this->table->name === $table->name) {
            return $this;
        }

        return new self($table, [], []);
    }

    public function listTables(): iterable
    {
        return [
            $this->table,
        ];
    }
}
