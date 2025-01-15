<?php

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\TableEntry;

interface ImportSourceFactory
{
    public function create(TableEntry $table): ImportSource;

    public function listTables(): iterable;
}
