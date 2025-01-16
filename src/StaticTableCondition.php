<?php

namespace EcomDev\MySQL2JSONL;

final readonly class StaticTableCondition implements TableCondition
{
    private function __construct(private bool $result)
    {
    }

    public static function alwaysTrue(): self
    {
        return new self(true);
    }


    public static function alwaysFalse(): self
    {
        return new self(false);
    }

    public function isSatisfiedBy(string $tableName, int $rows): bool
    {
        return $this->result;
    }

    public function withCondition(TableCondition $condition): TableCondition
    {
        return $this;
    }
}