<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\TableEntry;

/**
 * Service to clean-up table data on import
 */
interface ImportCleanupService
{
    public function cleanTable(TableEntry $table): void;
}
