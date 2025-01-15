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
use EcomDev\MySQL2JSONL\Import\ImportMode;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    #[Test]
    public function createsConfigurationWithOnlyHostAndDatabase()
    {
        $this->assertEquals(
            Configuration::fromMySQLConfiguration(
                MySQLConfiguration::fromHost("db", "magento"),
                4
            ),
            Configuration::fromJSON(
                <<<JSON
                {
                  "config": {
                      "connection": {
                        "host": "db",
                        "database": "magento"
                      }
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
            Configuration::fromMySQLConfiguration(
                MySQLConfiguration::fromHost("db", "magento2")
                    ->withCredentials("magento", "magento")
                    ->withPort(3307)
                    ->withCharset('utf8mb3')
                    ->withServerKey('path/to/some-secret-key'),
                4
            ),
            Configuration::fromJSON(
                <<<JSON
                {
                  "config": {
                      "connection": {
                        "host": "db",
                        "database": "magento2",
                        "user": "magento",
                        "password": "magento",
                        "port": 3307,
                        "charset": "utf8mb3",
                        "key": "path/to/some-secret-key"
                      }
                  }
                }
                JSON
            )
        );
    }

    #[Test]
    public function createsUnixSocketConfiguration()
    {
        $this->assertEquals(
            Configuration::fromMySQLConfiguration(
                MySQLConfiguration::fromUnixSocket("/var/run/db.sock", "magento2")
                    ->withCredentials("magento", "magento")
                    ->withCharset('utf8mb3')
                    ->withServerKey('path/to/some-secret-key'),
                4
            ),
            Configuration::fromJSON(
                <<<JSON
                {
                  "config": {
                      "connection": {
                        "unixSocket": "/var/run/db.sock",
                        "database": "magento2",
                        "user": "magento",
                        "password": "magento",
                        "charset": "utf8mb3",
                        "key": "path/to/some-secret-key"
                      }
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
                "config": {
                    "connection": {
                      "database": "magento"
                    }
                }
            }
            JSON,
            ConfigurationException::fromErrors([
                'config.connection.host' => 'The property host is required',
                'config.connection.unixSocket' => 'The property unixSocket is required',
                'config.connection' => 'Failed to match all schemas',
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
            Configuration::fromMySQLConfiguration(
                MySQLConfiguration::fromHost("db", "magento"),
                4
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
                  "config": {
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
                }
                JSON
            )
        );
    }

    #[Test]
    public function createsExcludeConditions()
    {
        $this->assertEquals(
            Configuration::fromMySQLConfiguration(
                MySQLConfiguration::fromHost("db", "magento"),
                4,
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
                  "config": { 
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
                }
                JSON
            )
        );
    }

    #[Test]
    public function allowsSpecifyingConcurrency()
    {
        $this->assertEquals(
            Configuration::fromMySQLConfiguration(
                MySQLConfiguration::fromHost("db", "magento"),
                40
            ),
            Configuration::fromJSON(
                <<<JSON
                {
                  "config": {
                      "connection": {
                        "host": "db",
                        "database": "magento"
                      },
                      "concurrency": 40
                  }
                }
                JSON
            )
        );
    }

    #[Test]
    public function allowsSpecifyingImportMode()
    {
        $this->assertEquals(
            Configuration::fromMySQLConfiguration(
                MySQLConfiguration::fromHost("db", "magento"),
                4
            )->withImportMode(ImportMode::Update),
            Configuration::fromJSON(
                <<<JSON
                {
                  "config": {
                      "connection": {
                        "host": "db",
                        "database": "magento"
                      },
                      "importMode": "update"
                  }
                }
                JSON
            )
        );
    }

    #[Test]
    public function allowsSpecifyingCustomBatchSize()
    {
        $this->assertEquals(
            Configuration::fromMySQLConfiguration(
                MySQLConfiguration::fromHost("db", "magento"),
                4
            )->withBatchSize(400),
            Configuration::fromJSON(
                <<<JSON
                {
                  "config": {
                      "connection": {
                        "host": "db",
                        "database": "magento"
                      },
                      "batchSize": 400
                  }
                }
                JSON
            )
        );
    }

    #[Test]
    public function createsPDOConnection()
    {
        $configuration = magentoWithSampleData("export");
        $configuration->createPDOConnection();

        $connection = $configuration->createPDOConnection();

        $this->assertSame(
            2040,
            $connection->query('SELECT COUNT(*) FROM `catalog_product_entity`')->fetch()[0]
        );
    }
}
