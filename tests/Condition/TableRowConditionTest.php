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

class TableRowConditionTest extends TestCase
{
    #[Test]
    public function minRowCount(): void
    {
        $condition = TableRowsCondition::minRows(1);

        $this->assertFalse(
            $condition->isSatisfiedBy(TableEntry::fromName('table_name_')),
            'Matches lower amount'
        );

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('table_name_')->withRows(1)),
            'Does not matches exact amount'
        );

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('table_name_')->withRows(20)),
            'Does not match larger amount'
        );
    }

    #[Test]
    public function maxRowCount(): void
    {
        $condition = TableRowsCondition::maxRows(100);

        $this->assertFalse(
            $condition->isSatisfiedBy(TableEntry::fromName('table_name_')->withRows(101)),
            'Matches lower amount'
        );

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('table_name_')->withRows(100)),
            'Does not matches exact amount'
        );

        $this->assertTrue(
            $condition->isSatisfiedBy(TableEntry::fromName('table_name_')->withRows(10)),
            'Does not match smaller amount'
        );
    }

    #[Test]
    public function combineType(): void
    {
        $condition = TableRowsCondition::maxRows(100)
            ->withCondition(TableRowsCondition::minRows(10));

        $this->assertTrue($condition->isSatisfiedBy(TableEntry::fromName('__')->withRows(11)));
        ;
        $this->assertTrue($condition->isSatisfiedBy(TableEntry::fromName('__')->withRows(99)));
        ;
        $this->assertTrue($condition->isSatisfiedBy(TableEntry::fromName('__')->withRows(100)));
        ;
        $this->assertFalse($condition->isSatisfiedBy(TableEntry::fromName('__')->withRows(101)));
        ;
        $this->assertFalse($condition->isSatisfiedBy(TableEntry::fromName('__')->withRows(9)));
        ;
    }
}
