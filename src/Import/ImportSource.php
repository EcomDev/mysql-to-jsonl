<?php

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\TableEntry;

interface ImportSource
{
    public function header(): array;
    public function rows(): iterable;
}
