<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

interface ExportOutput
{
    public function open(array $header): void;

    public function write(array $row): void;

    public function close(): void;
}
