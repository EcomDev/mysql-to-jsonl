<?php

namespace EcomDev\MySQL2JSONL\Import;

enum ImportMode
{
    case Truncate;
    case Update;

    public function isTruncate(): bool
    {
        return $this === self::Truncate;
    }
}
