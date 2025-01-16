<?php

namespace EcomDev\MySQL2JSONL;

interface TableCondition
{
    public function isSatisfiedBy(string $tableName, int $rows): bool;

    public function withCondition(TableCondition $condition): self;
}