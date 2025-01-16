<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Condition;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AndTableConditionTest extends TestCase
{
    #[Test]
    public function returnsTrueWhenAtLeastOneOfChildConditionsTrue()
    {
        $this->assertTrue(StaticTableCondition::alwaysFalse()
            ->withCondition(StaticTableCondition::alwaysFalse())
            ->withCondition(StaticTableCondition::alwaysTrue())
            ->withCondition(StaticTableCondition::alwaysFalse())
            ->isSatisfiedBy('some_table', 0));
    }

    #[Test]
    public function returnsTrueWhenNoneReturnedTrue()
    {
        $this->assertFalse(StaticTableCondition::alwaysFalse()
            ->withCondition(StaticTableCondition::alwaysFalse())
            ->withCondition(StaticTableCondition::alwaysFalse())
            ->withCondition(StaticTableCondition::alwaysFalse())
            ->isSatisfiedBy('some_table', 0));
    }
}
