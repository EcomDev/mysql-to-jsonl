<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

final class JsonExportOutput implements ExportOutput
{
    private ?array $header = null;

    public function __construct(
        private \SplFileObject $file
    ) {
    }

    public function open(array $header): void
    {
        $this->header = $header;

        $this->file->fwrite(json_encode($header) . PHP_EOL);
    }

    public function write(array $row): void
    {
        if ($this->header === null) {
            throw new ExportOutputWrittenBeforeOpenException();
        }

        if (count($this->header) !== count($row)) {
            throw new ExportOutputColumCountDoesNotMatchException();
        }

        $this->file->fwrite(json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL);
    }

    public function close(): void
    {
        unset($this->file);
    }
}
