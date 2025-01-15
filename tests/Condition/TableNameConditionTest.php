<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Condition;

use EcomDev\MySQL2JSONL\TableEntry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TableNameConditionTest extends TestCase
{
    #[Test]
    public function exactMatchType(): void
    {
        $this->assertFalse(
            TableNameCondition::exactMatch("table_name")
                ->isSatisfiedBy(TableEntry::fromName('table_name_!')),
            'Matches prefix'
        );

        $this->assertTrue(
            TableNameCondition::exactMatch("table_name")
                ->isSatisfiedBy(TableEntry::fromName('table_name')),
            'Does not match exact value'
        );
    }

    #[Test]
    public function startsWithType(): void
    {
        $condition = TableNameCondition::startsWith("table_name");

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('table_name_1')),
            "Failed to match prefix"
        );

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('table_name')),
            "Failed to match exact value"
        );

        $this->assertFalse(
            $condition->isSatisfiedBy(TableEntry::fromName('table_another')),
            "Failed to ignore wrong prefix"
        );
    }

    #[Test]
    public function endsWithType(): void
    {
        $condition = TableNameCondition::endsWith("_suffix");

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('table_name_suffix')),
            "Failed to match _suffix"
        );

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('_suffix')),
            "Failed to match exact value"
        );

        $this->assertFalse(
            $condition->isSatisfiedBy(TableEntry::fromName('table_suffix_another')),
            "Failed to ignore _suffix in the middle"
        );
    }

    #[Test]
    public function containsType(): void
    {
        $condition = TableNameCondition::contains("_catalog_");

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('table_catalog_name')),
            "Failed to match in the middle"
        );

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('_catalog_')),
            "Failed to match exact value"
        );

        $this->assertFalse(
            $condition->isSatisfiedBy(TableEntry::fromName('table_cat_another')),
            "Failed to ignore wrong name"
        );
    }

    #[Test]
    public function regexpType(): void
    {
        $condition = TableNameCondition::regexp("_cat.*?_");

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('table_catalog_name')),
            "Failed to match in the middle"
        );

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('_catalog_')),
            "Failed to match exact value"
        );

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('table_cat_another')),
            "Failed to match valid expression"
        );

        $this->assertFalse(
            $condition->isSatisfiedBy(TableEntry::fromName('table_ca_t_another')),
            "Failed to ignore wrong name"
        );
    }

    #[Test]
    public function combineType(): void
    {
        $condition = TableNameCondition::endsWith("_suffix")
            ->withCondition(TableNameCondition::startsWith("catalog_"))
            ->withCondition(TableNameCondition::contains("_name"));

        $this->assertTrue($condition->isSatisfiedBy(TableEntry::fromName('catalog_table_name_suffix')));
        $this->assertFalse($condition->isSatisfiedBy(TableEntry::fromName('catalog_table_name')));
    }
}
