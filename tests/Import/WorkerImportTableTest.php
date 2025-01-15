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
use EcomDev\MySQL2JSONL\TempDirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function EcomDev\MySQL2JSONL\magentoWithoutData;

class WorkerImportTableTest extends TestCase implements ProgressNotifier
{
    use ProgressNotifierAware;
    use TempDirectoryFixture;

    #[Test]
    public function importsDataFromSource()
    {
        $container = magentoWithoutData();

        $this->writeFile('store.jsonl', [
            ['store_id', 'code', 'name', 'website_id'],
            [2, 'english', 'English Store View', '1'],
            [3, 'french', 'French Store View', '1']
        ]);

        $service = WorkerImportServiceFactory::fromConfiguration($container->configuration, $this->path)
            ->create();

        $service->importTable(TableEntry::fromName('store')->withRows(2), $this);
        $service->await();

        $connection = $container->configuration->createPDOConnection();
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
}
