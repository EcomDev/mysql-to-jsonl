<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Condition;

use EcomDev\MySQL2JSONL\TableCondition;

final readonly class AnyTableCondition implements TableCondition
{
    public function __construct(private array $conditions)
    {

    }

    public function isSatisfiedBy(string $tableName, int $rows): bool
    {
        return array_reduce(
            $this->conditions,
            fn ($carry, TableCondition $condition) => $carry || $condition->isSatisfiedBy($tableName, $rows),
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