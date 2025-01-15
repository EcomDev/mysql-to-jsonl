<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

use EcomDev\MySQL2JSONL\Progress\ProgressNotifierAware;
use EcomDev\MySQL2JSONL\ProgressNotifier;
use EcomDev\MySQL2JSONL\TableEntry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function EcomDev\MySQL2JSONL\magentoWithSampleData;

class ExportTableTest extends TestCase implements ProgressNotifier, ExportOutput, ExportOutputFactory
{
    use ProgressNotifierAware;

    private array $output = [];

    private BlockingExportTable $export;

    protected function setUp(): void
    {
        $this->export = (new BlockingExportTableFactory(magentoWithSampleData("export"), $this))->create();
    }

    #[Test]
    public function exportsTableRowByRow()
    {
        $this->export->exportTable((TableEntry::fromName('store')->withRows(2)), $this);
        $this->assertEquals(
            [
                ['open', ['store_id', 'code', 'website_id', 'group_id', 'name', 'sort_order', 'is_active']],
                ['row', [0, 'admin', 0, 0, 'Admin', 0, 1]],
                ['row', [1, 'default', 1, 1, 'Default Store View', 0, 1]],
                ['close']
            ],
            $this->output
        );
    }

    #[Test]
    public function exportsHeadersForEmptyTable()
    {
        $this->export->exportTable((TableEntry::fromName('customer_visitor')->withRows(0)), $this);
        $this->assertEquals(
            [
                ['open', [
                    'visitor_id',
                    'customer_id',
                    'session_id',
                    'created_at',
                    'last_visit_at',
                ]],
                ['close']
            ],
            $this->output
        );
    }


    #[Test]
    public function executesNotifications()
    {
        $this->export->exportTable((TableEntry::fromName('store')->withRows(2)), $this);

        $this->assertEquals(
            [
                ['start', 'store', 2],
                ['update', 'store', 1],
                ['update', 'store', 2],
                ['finish', 'store'],
            ],
            $this->progress
        );
    }

    public function open(array $header): void
    {
        $this->output[] = ['open', $header];
    }

    public function write(array $row): void
    {
        $this->output[] = ['row', $row];
    }

    public function close(): void
    {
        $this->output[] = ['close'];
    }

    public function create(string $tableName): ExportOutput
    {
        return $this;
    }
}
