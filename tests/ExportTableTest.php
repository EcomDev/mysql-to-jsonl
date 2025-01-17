<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;

use Amp\Future;
use Amp\Pipeline\Queue;
use EcomDev\MySQL2JSONL\Condition\TableNameCondition;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExportTableTest extends TestCase implements ProgressNotifier
{
    private array $notifications = [];

    private function exportTable(string $tableName): array
    {
        $config = magentoWithSampleData("export");
        $connectionPool = $config->createConnectionPool();
        $factory = new ExportTableFactory($connectionPool, $this);
        $exporter = $factory->createExport($tableName);
        $queue = new Queue(100);
        $exporter->run($queue);
        return queueToArray($queue);
    }

    #[Test]
    public function exportsTableRowByRow()
    {
        $this->assertEquals(
            [
                ['store_id', 'code', 'website_id', 'group_id', 'name', 'sort_order', 'is_active'],
                [0, 'admin', 0, 0, 'Admin', 0, 1],
                [1, 'default', 1, 1, 'Default Store View', 0, 1],
            ],
            $this->exportTable('store')
        );
    }

    #[Test]
    public function exportsHeadersForEmptyTable()
    {
        $this->assertEquals(
            [
                [
                    'visitor_id',
                    'customer_id',
                    'session_id',
                    'created_at',
                    'last_visit_at',
                ]
            ],
            $this->exportTable('customer_visitor')
        );
    }


    #[Test]
    public function executesNotifications()
    {
        $this->exportTable('store');

        $this->assertEquals(
            [
                ['start', 'store', 2],
                ['update', 'store', 1],
                ['update', 'store', 2],
                ['finish', 'store'],
            ],
            $this->notifications
        );
    }

    #[Test]
    public function usesConfigurationToListExportableTables()
    {
        $config = magentoWithSampleData("export")
            ->withIncludeCondition(TableNameCondition::startsWith('catalog_product_entity_'))
            ->withExcludeCondition(TableNameCondition::endsWith('_varchar'))
            ->withExcludeCondition(TableNameCondition::contains('gallery'));

        $connectionPool = $config->createConnectionPool();
        $factory = new ExportTableFactory($connectionPool, $this);

        $this->assertEquals(
            [
                'catalog_product_entity_datetime',
                'catalog_product_entity_decimal',
                'catalog_product_entity_int',
                'catalog_product_entity_text',
                'catalog_product_entity_tier_price'
            ],
            $factory->tablesToExport($config)
        );
    }

    public function start(string $name, int $total): void
    {
        $this->notifications[] = ['start', $name, $total];
    }

    public function update(string $name, int $current): void
    {
        $this->notifications[] = ['update', $name, $current];
    }

    public function finish(string $name): void
    {
        $this->notifications[] = ['finish', $name];
    }
}
