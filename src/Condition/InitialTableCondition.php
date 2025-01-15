<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Condition;

use EcomDev\MySQL2JSONL\TableCondition;
use EcomDev\MySQL2JSONL\TableEntry;

final readonly class InitialTableCondition implements TableCondition
{
    use ComposeAnyCondition;

    private function __construct(private bool $result)
    {
    }

    public static function alwaysTrue(): self
    {
        return new self(true);
    }


    public static function alwaysFalse(): self
    {
        return new self(false);
    }

    public function isSatisfiedBy(TableEntry $table): bool
    {
        return $this->result;
    }
}
