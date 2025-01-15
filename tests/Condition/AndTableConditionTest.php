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

class AndTableConditionTest extends TestCase
{
    #[Test]
    public function returnsTrueWhenAtLeastOneOfChildConditionsTrue()
    {
        $this->assertTrue(InitialTableCondition::alwaysFalse()
            ->withCondition(InitialTableCondition::alwaysFalse())
            ->withCondition(InitialTableCondition::alwaysTrue())
            ->withCondition(InitialTableCondition::alwaysFalse())
            ->isSatisfiedBy(TableEntry::fromName('some_table')));
    }

    #[Test]
    public function returnsTrueWhenNoneReturnedTrue()
    {
        $this->assertFalse(InitialTableCondition::alwaysFalse()
            ->withCondition(InitialTableCondition::alwaysFalse())
            ->withCondition(InitialTableCondition::alwaysFalse())
            ->withCondition(InitialTableCondition::alwaysFalse())
            ->isSatisfiedBy(TableEntry::fromName('some_table')));
    }
}
