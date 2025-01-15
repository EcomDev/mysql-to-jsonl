<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

final class NullExportOutput implements ExportOutput
{
    public function open(array $header): void
    {
        // TODO: Implement open() method.
    }

    public function write(array $row): void
    {
        // TODO: Implement write() method.
    }

    public function close(): void
    {
        // TODO: Implement close() method.
    }
}
