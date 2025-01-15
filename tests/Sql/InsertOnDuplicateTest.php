<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Sql;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class InsertOnDuplicateTest extends TestCase
{
    #[Test]
    public function generatesSingleRow()
    {
        $this->assertEquals(
            'INSERT INTO `table1` (`column_one`,`column_two`) VALUES (?,?)',
            (new InsertOnDuplicate())
                ->generate('table1', ['column_one', 'column_two'], 1)
        );
    }

    #[Test]
    public function generatesMultipleRows()
    {
        $this->assertEquals(
            'INSERT INTO `table1` (`column_one`,`column_two`) VALUES (?,?),(?,?),(?,?)',
            (new InsertOnDuplicate())
                ->generate('table1', ['column_one', 'column_two'], 3)
        );
    }

    #[Test]
    public function generatesSingleRowWithOnDuplicate()
    {
        $this->assertEquals(
            'INSERT INTO `table1` (`column_one`,`column_two`) VALUES (?,?) ON DUPLICATE KEY UPDATE `column_two` = VALUES(`column_two`)',
            (new InsertOnDuplicate())
                ->generate(
                    'table1',
                    ['column_one', 'column_two'],
                    1,
                    ['column_two']
                )
        );
    }
}
