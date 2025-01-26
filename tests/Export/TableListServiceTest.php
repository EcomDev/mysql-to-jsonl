<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

use EcomDev\MySQL2JSONL\Condition\TableNameCondition;
use EcomDev\MySQL2JSONL\TableEntry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function EcomDev\MySQL2JSONL\magentoWithSampleData;

class TableListServiceTest extends TestCase
{
    #[Test]
    public function usesConfigurationToListExportableTables()
    {
        $config = magentoWithSampleData("export")
            ->withIncludeCondition(TableNameCondition::startsWith('catalog_product_entity_'))
            ->withExcludeCondition(TableNameCondition::endsWith('_varchar'))
            ->withExcludeCondition(TableNameCondition::contains('gallery'));


        $expectedTableValues = [
            TableEntry::fromName('catalog_product_entity_datetime')->withRows(6),
            TableEntry::fromName('catalog_product_entity_decimal')->withRows(3903),
            TableEntry::fromName('catalog_product_entity_int')->withRows(12480),
            TableEntry::fromName('catalog_product_entity_text')->withRows(2652),
            TableEntry::fromName('catalog_product_entity_tier_price')->withRows(0)
        ];

        $actualTableValues = (new TableListService($config))->tablesToExport();

        foreach ($expectedTableValues as $index => $expectedTable) {
            $this->assertArrayHasKey(
                $index,
                $actualTableValues,
                'Table list does not match'
            );

            $this->assertEquals($expectedTable->name, $actualTableValues[$index]->name, 'Table name does not match expected');
            $this->assertEqualsWithDelta(
                $expectedTable->rowCount,
                $actualTableValues[$index]->rowCount,
                $expectedTable->rowCount * 0.2
            );
        }
    }
}
