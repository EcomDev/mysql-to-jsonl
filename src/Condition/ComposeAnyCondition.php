<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Condition;

use EcomDev\MySQL2JSONL\TableCondition;

trait ComposeAnyCondition
{
    public function withCondition(TableCondition $condition): TableCondition
    {
        return match ($this::class) {
            InitialTableCondition::class => new AnyTableCondition([$condition]),
            default => new AnyTableCondition([$this, $condition]),
        };
    }
}
