<?php

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\ProgressNotifier;
use EcomDev\MySQL2JSONL\TableEntry;

interface ImportService
{
    public function importTable(TableEntry $table, ProgressNotifier $notifier): void;
}
