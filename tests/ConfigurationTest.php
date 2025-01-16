<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;


use Amp\Mysql\MysqlConfig;
use EcomDev\MySQL2JSONL\Condition\AndTableCondition;
use EcomDev\MySQL2JSONL\Condition\TableNameCondition;
use EcomDev\MySQL2JSONL\Condition\TableRowsCondition;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    #[Test]
    public function createsConfigurationWithOnlyHostAndDatabase()
    {
        $this->assertEquals(
            Configuration::fromMysqlConfig(
                MysqlConfig::fromAuthority("db", "root", "", "magento")
                    ->withSqlMode('')
                    ->withCharset('utf8mb4', 'utf8mb4_unicode_ci')
            ),
            Configuration::fromJSON(
                <<<JSON
                {
                  "connection": {
                    "host": "db",
                    "database": "magento"
                  }
                }
                JSON
            )
        );
    }

    #[Test]
    public function createsConfigurationWithAllConnectionSettings()
    {
        $this->assertEquals(
            Configuration::fromMysqlConfig(
                MysqlConfig::fromAuthority("db:3307", "magento", "magento", "magento2")
                    ->withCompression()
                    ->withKey('some-secret-key')
                    ->withSqlMode('ansi')
                    ->withCharset('utf8mb3', 'utf8mb3_general_ci')
            ),
            Configuration::fromJSON(
                <<<JSON
                {
                  "connection": {
                    "host": "db",
                    "database": "magento2",
                    "user": "magento",
                    "password": "magento",
                    "port": 3307,
                    "charset": "utf8mb3",
                    "collate": "utf8mb3_general_ci",
                    "sqlMode": "ansi",
                    "useCompression": true,
                    "key": "some-secret-key"
                  }
                }
                JSON
            )
        );
    }

    #[Test]
    public function reportsErrorOnJsonValidation()
    {
        $this->assertConfigException(
            <<<JSON
            {
              "connection": {
                "database": "magento"
              }
            }
            JSON,
            ConfigurationException::fromErrors([
                'connection.host' => 'The property host is required'
            ])
        );
    }

    #[Test]
    public function generatesSimpleTableIncludeConditions()
    {
        $this->assertConfigException(
            <<<JSON
            {
              "connection": {
                "database": "magento"
              }
            }
            JSON,
            ConfigurationException::fromErrors([
                'connection.host' => 'The property host is required'
            ])
        );
    }


    private function assertConfigException(string $json, ConfigurationException $error): void
    {
        $this->expectException(ConfigurationException::class);

        try {
            Configuration::fromJSON($json);
        } catch (ConfigurationException $e) {
            $this->assertEquals($error, $e);
            throw $e;
        }
    }

    #[Test]
    public function createsIncludeConditions()
    {
        $this->assertEquals(
            Configuration::fromMysqlConfig(
                MysqlConfig::fromAuthority("db", "root", "", "magento")
                    ->withSqlMode('')
                    ->withCharset('utf8mb4', 'utf8mb4_unicode_ci')
            )
            ->withIncludeCondition(TableNameCondition::exactMatch("table_name"))
            ->withIncludeCondition(TableNameCondition::startsWith("catalog_"))
            ->withIncludeCondition(TableNameCondition::endsWith("_index"))
            ->withIncludeCondition(TableNameCondition::contains("_tmp_"))
            ->withIncludeCondition(TableNameCondition::regexp("_.*_"))
            ->withIncludeCondition(TableRowsCondition::minRows(10))
            ->withIncludeCondition(TableRowsCondition::maxRows(100))
            ->withIncludeCondition(AndTableCondition::from(
                TableNameCondition::regexp("_.*_"),
                TableRowsCondition::minRows(10),
                TableRowsCondition::maxRows(100)
            )),
            Configuration::fromJSON(
                <<<JSON
                {
                  "connection": {
                    "host": "db",
                    "database": "magento"
                  },
                  "includeTables": [
                    "table_name",
                    {"startsWith": "catalog_"},
                    {"endsWith": "_index"},
                    {"contains": "_tmp_"},
                    {"regexp": "_.*_"},
                    {"minRows": 10},
                    {"maxRows": 100},
                    {"and": [
                        {"regexp": "_.*_"},
                        {"minRows": 10},
                        {"maxRows": 100}
                     ]}
                  ]
                }
                JSON
            )
        );
    }

    #[Test]
    public function createsExcludeConditions()
    {
        $this->assertEquals(
            Configuration::fromMysqlConfig(
                MysqlConfig::fromAuthority("db", "root", "", "magento")
                    ->withSqlMode('')
                    ->withCharset('utf8mb4', 'utf8mb4_unicode_ci')
            )
                ->withExcludeCondition(TableNameCondition::exactMatch("table_name"))
                ->withExcludeCondition(TableNameCondition::startsWith("catalog_"))
                ->withExcludeCondition(TableNameCondition::endsWith("_index"))
                ->withExcludeCondition(TableNameCondition::contains("_tmp_"))
                ->withExcludeCondition(TableNameCondition::regexp("_.*_"))
                ->withExcludeCondition(TableRowsCondition::minRows(10))
                ->withExcludeCondition(TableRowsCondition::maxRows(100))
                ->withExcludeCondition(AndTableCondition::from(
                    TableNameCondition::regexp("_.*_"),
                    TableRowsCondition::minRows(10),
                    TableRowsCondition::maxRows(100)
                )),
            Configuration::fromJSON(
                <<<JSON
                {
                  "connection": {
                    "host": "db",
                    "database": "magento"
                  },
                  "excludeTables": [
                    "table_name",
                    {"startsWith": "catalog_"},
                    {"endsWith": "_index"},
                    {"contains": "_tmp_"},
                    {"regexp": "_.*_"},
                    {"minRows": 10},
                    {"maxRows": 100},
                    {"and": [
                        {"regexp": "_.*_"},
                        {"minRows": 10},
                        {"maxRows": 100}
                     ]}
                  ]
                }
                JSON
            )
        );
    }
}
