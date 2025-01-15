<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;

final readonly class TableEntry
{
    public function __construct(
        public string $name,
        public int    $rowCount
    ) {
    }

    public static function fromName(string $name): static
    {
        return new self($name, 0);
    }

    public function withRows(int $rows): self
    {
        return new self($this->name, $rows);
    }
}
