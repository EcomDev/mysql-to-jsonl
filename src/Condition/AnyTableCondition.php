<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Condition;

use EcomDev\MySQL2JSONL\TableCondition;
use EcomDev\MySQL2JSONL\TableEntry;

final readonly class AnyTableCondition implements TableCondition
{
    public function __construct(private array $conditions)
    {
    }

    public function isSatisfiedBy(TableEntry $table): bool
    {
        return array_reduce(
            $this->conditions,
            fn ($carry, TableCondition $condition) => $carry || $condition->isSatisfiedBy($table),
            false
        );
    }

    public function withCondition(TableCondition $condition): TableCondition
    {
        $conditions = $this->conditions;
        $conditions[] = $condition;
        return new self($conditions);
    }
}
