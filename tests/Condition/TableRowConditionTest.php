<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Condition;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TableRowConditionTest extends TestCase
{
    #[Test]
    public function minRowCount(): void
    {
        $condition = TableRowsCondition::minRows(1);

        $this->assertFalse(
            $condition->isSatisfiedBy("table_name_", 0),
            'Matches lower amount'
        );

        $this->assertTrue(
            $condition->isSatisfiedBy("table_name_", 1),
            'Does not matches exact amount'
        );

        $this->assertTrue(
            $condition->isSatisfiedBy("table_name_", 20),
            'Does not match larger amount'
        );
    }

    #[Test]
    public function maxRowCount(): void
    {
        $condition = TableRowsCondition::maxRows(100);

        $this->assertFalse(
            $condition->isSatisfiedBy("table_name_", 101),
            'Matches lower amount'
        );

        $this->assertTrue(
            $condition->isSatisfiedBy("table_name_", 100),
            'Does not matches exact amount'
        );

        $this->assertTrue(
            $condition->isSatisfiedBy("table_name_", 10),
            'Does not match smaller amount'
        );
    }

    #[Test]
    public function combineType(): void
    {
        $condition = TableRowsCondition::maxRows(100)
            ->withCondition(TableRowsCondition::minRows(10));

        $this->assertTrue($condition->isSatisfiedBy("__", 11));
        $this->assertTrue($condition->isSatisfiedBy("__", 99));
        $this->assertTrue($condition->isSatisfiedBy("__", 100));
        $this->assertFalse($condition->isSatisfiedBy("__", 101));
        $this->assertFalse($condition->isSatisfiedBy("__", 9));
    }
}
