<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

use EcomDev\MySQL2JSONL\Condition\TableNameCondition;
use EcomDev\MySQL2JSONL\Progress\ProgressNotifierAware;
use EcomDev\MySQL2JSONL\ProgressNotifier;
use EcomDev\MySQL2JSONL\TempDirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function EcomDev\MySQL2JSONL\magentoWithSampleData;

class WorkerExportServiceTest extends TestCase implements ProgressNotifier
{
    use TempDirectoryFixture;
    use ProgressNotifierAware;

    #[Test]
    public function exportsDataIntoRealDirectory()
    {
        $config = magentoWithSampleData("export")
            ->withIncludeCondition(TableNameCondition::exactMatch('store'))
            ->withIncludeCondition(TableNameCondition::startsWith('catalog_product_entity'));

        $tableService = new TableListService($config);
        $exportService = WorkerExportServiceFactory::fromConfiguration($config, $this->path)->create();

        foreach ($tableService->tablesToExport() as $table) {
            $exportService->exportTable(
                $table,
                $this
            );
        }

        $exportService->await();

        $this->assertEquals(
            [
                'catalog_product_entity.jsonl',
                'catalog_product_entity_datetime.jsonl',
                'catalog_product_entity_decimal.jsonl',
                'catalog_product_entity_gallery.jsonl',
                'catalog_product_entity_int.jsonl',
                'catalog_product_entity_media_gallery.jsonl',
                'catalog_product_entity_media_gallery_value.jsonl',
                'catalog_product_entity_media_gallery_value_to_entity.jsonl',
                'catalog_product_entity_media_gallery_value_video.jsonl',
                'catalog_product_entity_text.jsonl',
                'catalog_product_entity_tier_price.jsonl',
                'catalog_product_entity_varchar.jsonl',
                'store.jsonl'
            ],
            array_map(fn ($v) => basename($v), glob($this->path . DIRECTORY_SEPARATOR . '*.jsonl'))
        );
    }
}
