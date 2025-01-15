<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;

interface TableCondition
{
    public function isSatisfiedBy(TableEntry $table): bool;

    public function withCondition(TableCondition $condition): TableCondition;
}
