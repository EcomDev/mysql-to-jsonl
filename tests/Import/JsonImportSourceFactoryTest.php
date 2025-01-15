<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\Condition\TableNameCondition;
use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\MySQLConfiguration;
use EcomDev\MySQL2JSONL\TableEntry;
use EcomDev\MySQL2JSONL\TempDirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class JsonImportSourceFactoryTest extends TestCase
{
    use TempDirectoryFixture;

    #[Test]
    public function errorsWhenInputDirectoryDoesNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);
        new JsonImportSourceFactory(
            $this->path,
            Configuration::fromMySQLConfiguration(
                MySQLConfiguration::fromHost('localhost', 'magento'),
                10
            )
        );
    }

    #[Test]
    public function returnsAllFilesWithRowCountInDirectoryThatEndInJsonl()
    {
        $this->writeFiles([
            'catalog_product_entity_datetime.jsonl' => [[],[],[]],
            'catalog_product_entity_int.txt' => [[],[],[]],
            'catalog_product_entity.jsonl' => [[],[],[],[]],
            'catalog_product_entity_int.jsonl' => [[],[]],
        ]);

        $factory = new JsonImportSourceFactory(
            $this->path,
            Configuration::fromMySQLConfiguration(
                MySQLConfiguration::fromHost('localhost', 'magento'),
                10
            )
        );

        $this->assertEqualsCanonicalizing(
            [
                TableEntry::fromName('catalog_product_entity')->withRows(3),
                TableEntry::fromName('catalog_product_entity_int')->withRows(1),
                TableEntry::fromName('catalog_product_entity_datetime')->withRows(2)
            ],
            iterator_to_array($factory->listTables())
        );
    }

    #[Test]
    public function filtersOutputFilesBasedOnConfiguration()
    {
        $this->writeFiles([
            'catalog_product_entity_int.txt' => [[],[],[]],
            'catalog_product_entity.jsonl' => [[],[],[],[]],
            'catalog_product_entity_int.jsonl' => [[],[]],
        ]);

        $factory = new JsonImportSourceFactory(
            $this->path,
            Configuration::fromMySQLConfiguration(
                MySQLConfiguration::fromHost('localhost', 'magento'),
                10
            )
            ->withIncludeCondition(TableNameCondition::contains('product'))
            ->withExcludeCondition(TableNameCondition::endsWith('_int'))
        );

        $this->assertEqualsCanonicalizing(
            [
                TableEntry::fromName('catalog_product_entity')->withRows(3),
            ],
            iterator_to_array($factory->listTables())
        );
    }

    #[Test]
    public function createsInputSourceFromExistingFile()
    {
        $this->writeFiles([
            'catalog_product_entity.jsonl' => [["entity_id", "sku", "type_id"]],
        ]);

        $factory = new JsonImportSourceFactory(
            $this->path,
            Configuration::fromMySQLConfiguration(
                MySQLConfiguration::fromHost('localhost', 'magento'),
                10
            )
        );

        $inputSource = $factory->create(TableEntry::fromName('catalog_product_entity')->withRows(0));
        $this->assertEquals(
            ['entity_id', 'sku', 'type_id'],
            $inputSource->header()
        );
    }

    private function writeFiles(array $files): void
    {
        foreach ($files as $file => $content) {
            $this->writeFile($file, $content);
        }
    }
}
