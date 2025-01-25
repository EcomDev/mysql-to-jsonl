<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\ContainerWithConfig;
use EcomDev\MySQL2JSONL\Progress\ProgressNotifierAware;
use EcomDev\MySQL2JSONL\ProgressNotifier;
use EcomDev\MySQL2JSONL\TableEntry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function EcomDev\MySQL2JSONL\magentoWithoutData;

class ImportTableTest extends TestCase implements ProgressNotifier
{
    use ProgressNotifierAware;

    private ContainerWithConfig $containerWithConfig;

    protected function setUp(): void
    {
        $this->containerWithConfig = magentoWithoutData();
    }

    #[Test]
    public function importsTableDataFromSourceWithTruncate()
    {
        $source = FakeImportSource::fromTableName('store')
            ->withHeader(['store_id', 'code', 'name', 'website_id'])
            ->withRow([2, 'english', 'English Store View', '1'])
            ->withRow([3, 'french', 'French Store View', '1']);

        $factory = BlockingImportServiceFactory::createFromConfiguration(
            $this->containerWithConfig->configuration,
            $source
        );

        $import = $factory->create();

        $import->cleanTable($source->table);
        $import->importTable($source->table, $this);

        $connection = $this->containerWithConfig->configuration->createPDOConnection();
        $result = $connection->prepare('SELECT store_id, code, name, website_id FROM store');
        $result->execute();

        $this->assertEquals(
            [
                [2, 'english', 'English Store View', 1],
                [3, 'french', 'French Store View', 1],
            ],
            $result->fetchAll()
        );
    }

    #[Test]
    public function appendsExistingStoresWhenUpdateStrategyIsUsed()
    {
        $source = FakeImportSource::fromTableName('store')
            ->withHeader(['store_id', 'code', 'name', 'website_id'])
            ->withRow([1, 'custom', 'Custom Store View', '1'])
            ->withRow([2, 'english', 'English Store View', '1'])
            ->withRow([3, 'french', 'French Store View', '1']);

        $factory = BlockingImportServiceFactory::createFromConfiguration(
            $this->containerWithConfig->configuration->withImportMode(ImportMode::Update),
            $source
        );

        $import = $factory->create();
        $import->cleanTable($source->table);
        $import->importTable($source->table, $this);

        $connection = $this->containerWithConfig->configuration->createPDOConnection();
        $result = $connection->prepare('SELECT store_id, code, name, website_id FROM store');
        $result->execute();

        $this->assertEquals(
            [
                [0, 'admin', 'Admin', 0],
                [1, 'custom', 'Custom Store View', 1],
                [2, 'english', 'English Store View', 1],
                [3, 'french', 'French Store View', 1],
            ],
            $result->fetchAll()
        );
    }

    #[Test]
    public function notifiesOfTheImportPorgress()
    {
        $source = FakeImportSource::fromTableName('store')
            ->withHeader(['store_id', 'code', 'name', 'website_id'])
            ->withRow([1, 'custom', 'Custom Store View', '1'])
            ->withRow([2, 'english', 'English Store View', '1'])
            ->withRow([3, 'french', 'French Store View', '1']);

        $factory = BlockingImportServiceFactory::createFromConfiguration(
            $this->containerWithConfig->configuration->withImportMode(ImportMode::Update),
            $source
        );

        $import = $factory->create();
        $import->cleanTable($source->table);
        $import->importTable($source->table, $this);

        $this->assertEquals(
            [
                ['start', 'store', 3],
                ['update', 'store', 3],
                ['finish', 'store']
            ],
            $this->progress
        );
    }

    #[Test]
    public function batchesLongInputs()
    {
        $source = FakeImportSource::fromTableName('store')
            ->withHeader(['store_id', 'code', 'name', 'website_id']);

        $expectedResult = [];
        for ($i = 2; $i < 10000; $i++) {
            $row = [$i, 'custom' . $i, 'Custom Store View', 4];
            $source = $source->withRow($row);
            $expectedResult[] = $row;
        }

        $factory = BlockingImportServiceFactory::createFromConfiguration(
            $this->containerWithConfig->configuration
                ->withImportMode(ImportMode::Update)
                ->withBatchSize(100),
            $source
        );

        $import = $factory->create();
        $import->cleanTable($source->table);
        $import->importTable($source->table, $this);

        $connection = $this->containerWithConfig->configuration->createPDOConnection();
        $result = $connection->prepare('SELECT store_id, code, name, website_id FROM store');
        $result->execute();

        $this->assertEquals(
            [
                [0, 'admin', 'Admin', 0],
                [1, 'default', 'Default Store View', 1],
                ...$expectedResult,
            ],
            $result->fetchAll()
        );
    }
}
