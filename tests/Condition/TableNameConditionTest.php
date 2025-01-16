<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Condition;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TableNameConditionTest extends TestCase
{
    #[Test]
    public function exactMatchType(): void
    {
        $this->assertFalse(
            TableNameCondition::exactMatch("table_name")
                ->isSatisfiedBy("table_name_!", 0),
            'Matches prefix'
        );

        $this->assertTrue(
            TableNameCondition::exactMatch("table_name")
                ->isSatisfiedBy("table_name", 0),
            'Does not match exact value'
        );
    }

    #[Test]
    public function startsWithType(): void
    {
        $condition = TableNameCondition::startsWith("table_name");

        $this->assertTrue(
            $condition->isSatisfiedBy("table_name_1", 0),
            "Failed to match prefix"
        );

        $this->assertTrue(
            $condition->isSatisfiedBy("table_name", 0),
            "Failed to match exact value"
        );

        $this->assertFalse(
            $condition->isSatisfiedBy("table_another", 0),
            "Failed to ignore wrong prefix"
        );
    }

    #[Test]
    public function endsWithType(): void
    {
        $condition = TableNameCondition::endsWith("_suffix");

        $this->assertTrue(
            $condition->isSatisfiedBy("table_name_suffix", 0),
            "Failed to match _suffix"
        );

        $this->assertTrue(
            $condition->isSatisfiedBy("_suffix", 0),
            "Failed to match exact value"
        );

        $this->assertFalse(
            $condition->isSatisfiedBy("table_suffix_another", 0),
            "Failed to ignore _suffix in the middle"
        );
    }

    #[Test]
    public function containsType(): void
    {
        $condition = TableNameCondition::contains("_catalog_");

        $this->assertTrue(
            $condition->isSatisfiedBy("table_catalog_name", 0),
            "Failed to match in the middle"
        );

        $this->assertTrue(
            $condition->isSatisfiedBy("_catalog_", 0),
            "Failed to match exact value"
        );

        $this->assertFalse(
            $condition->isSatisfiedBy("table_cat_another", 0),
            "Failed to ignore wrong name"
        );
    }

    #[Test]
    public function regexpType(): void
    {
        $condition = TableNameCondition::regexp("_cat.*?_");

        $this->assertTrue(
            $condition->isSatisfiedBy("table_catalog_name", 0),
            "Failed to match in the middle"
        );

        $this->assertTrue(
            $condition->isSatisfiedBy("_catalog_", 0),
            "Failed to match exact value"
        );

        $this->assertTrue(
            $condition->isSatisfiedBy("table_cat_another", 0),
            "Failed to match valid expression"
        );

        $this->assertFalse(
            $condition->isSatisfiedBy("table_ca_t_another", 0),
            "Failed to ignore wrong name"
        );
    }

    #[Test]
    public function combineType(): void
    {
        $condition = TableNameCondition::endsWith("_suffix")
            ->withCondition(TableNameCondition::startsWith("catalog_"))
            ->withCondition(TableNameCondition::contains("_name"));

        $this->assertTrue($condition->isSatisfiedBy("catalog_table_name_suffix", 0));
        $this->assertFalse($condition->isSatisfiedBy("catalog_table_name", 0));
    }
}
