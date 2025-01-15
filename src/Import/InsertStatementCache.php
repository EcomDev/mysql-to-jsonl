<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\Sql\InsertOnDuplicate;
use PDOStatement;

class InsertStatementCache
{
    private function __construct(
        private readonly \PDO $connection,
        private readonly InsertOnDuplicate $insertOnDuplicate,
        private string $tableName,
        private array $columns,
        private array $statements
    ) {
    }

    public static function create(
        \PDO $connection,
        InsertOnDuplicate $insertOnDuplicate,
        string $tableName,
        array $columns
    ): self {
        return new self($connection, $insertOnDuplicate, $tableName, $columns, []);
    }

    public function prepareStatement(int $rows): PDOStatement
    {
        if (!isset($this->statements[$rows])) {
            $this->statements[$rows] = $this->connection->prepare(
                $this->insertOnDuplicate->generate($this->tableName, $this->columns, $rows, $this->columns)
            );
        }

        return $this->statements[$rows];
    }
}
