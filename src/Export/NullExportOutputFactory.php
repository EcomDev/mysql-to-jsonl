<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

final class NullExportOutputFactory implements ExportOutputFactory
{
    public function create(string $tableName): ExportOutput
    {
        return new NullExportOutput();
    }
}
