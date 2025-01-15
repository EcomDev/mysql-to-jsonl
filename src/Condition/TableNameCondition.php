<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Condition;

use EcomDev\MySQL2JSONL\TableCondition;
use EcomDev\MySQL2JSONL\TableEntry;

final readonly class TableNameCondition implements TableCondition
{
    use ComposeAndCondition;

    private const EXACT_MATCH = 'exact';
    private const STARTS_WITH = 'starts';
    private const ENDS_WITH = 'ends';
    private const CONTAINS = 'contains';
    private const REGEXP = 'regexp';

    private function __construct(private string $tableName, private string $matchType)
    {
    }

    public static function exactMatch(string $tableName): TableNameCondition
    {
        return new TableNameCondition($tableName, self::EXACT_MATCH);
    }

    public static function startsWith(string $tableName): TableNameCondition
    {
        return new TableNameCondition($tableName, self::STARTS_WITH);
    }

    public static function endsWith(string $tableName): TableNameCondition
    {
        return new TableNameCondition($tableName, self::ENDS_WITH);
    }

    public static function contains(string $tableName): TableNameCondition
    {
        return new TableNameCondition($tableName, self::CONTAINS);
    }

    public static function regexp(string $regexp): TableNameCondition
    {
        return new TableNameCondition($regexp, self::REGEXP);
    }


    public function isSatisfiedBy(TableEntry $table): bool
    {
        return match ($this->matchType) {
            self::EXACT_MATCH => $table->name === $this->tableName,
            self::STARTS_WITH => str_starts_with($table->name, $this->tableName),
            self::ENDS_WITH => str_ends_with($table->name, $this->tableName),
            self::CONTAINS => str_contains($table->name, $this->tableName),
            self::REGEXP => !!preg_match($this->tableName, $table->name),
            default => false
        };
    }
}
