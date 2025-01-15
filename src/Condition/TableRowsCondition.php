<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Condition;

use EcomDev\MySQL2JSONL\TableCondition;
use EcomDev\MySQL2JSONL\TableEntry;

class TableRowsCondition implements TableCondition
{
    use ComposeAndCondition;

    private const MIN_ROWS = 'min_rows';
    private const MAX_ROWS = 'max_rows';

    public function __construct(
        private readonly int $rowsCount,
        private readonly string $type
    ) {
    }

    public static function minRows(int $minRows): self
    {
        return new self($minRows, self::MIN_ROWS);
    }

    public static function maxRows(int $maxRows): self
    {
        return new self($maxRows, self::MAX_ROWS);
    }

    public function isSatisfiedBy(TableEntry $table): bool
    {
        return match ($this->type) {
            self::MIN_ROWS => $table->rowCount >= $this->rowsCount,
            self::MAX_ROWS => $table->rowCount <= $this->rowsCount,
            default => false,
        };
    }
}
