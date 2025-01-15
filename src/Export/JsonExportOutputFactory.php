<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

use SplFileObject;

final class JsonExportOutputFactory implements ExportOutputFactory
{
    public function __construct(private readonly string $path)
    {
    }

    public function create(string $tableName): ExportOutput
    {
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }

        return new JsonExportOutput(
            new SplFileObject(
                $this->path . DIRECTORY_SEPARATOR . $tableName . '.jsonl',
                'w'
            )
        );
    }
}
