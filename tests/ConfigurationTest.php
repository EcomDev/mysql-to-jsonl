<?php

namespace EcomDev\MySQL2JSONL;

use Amp\Mysql\MysqlConfig;
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
}
