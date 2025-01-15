<?php

namespace EcomDev\MySQL2JSONL;

use PDO;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MySQLConfigurationTest extends TestCase
{
    #[Test]
    public function generatesDsnForUnixSocket()
    {
        $configuration = MySQLConfiguration::fromUnixSocket(
            '/var/run/mysqld/mysqld.sock',
            'magento_db'
        );

        $this->assertEquals(
            'mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=magento_db;charset=utf8mb4',
            $configuration->generateDSN()
        );
    }

    #[Test]
    public function createsFromHost()
    {
        $configuration = MySQLConfiguration::fromHost(
            'db',
            'db_name'
        );

        $this->assertEquals(
            'mysql:host=db;port=3306;dbname=db_name;charset=utf8mb4',
            $configuration->generateDSN()
        );
    }

    #[Test]
    public function allowsToSpecifyPort()
    {
        $configuration = MySQLConfiguration::fromHost('db', 'magento')
            ->withPort(3307);

        $this->assertEquals(
            'mysql:host=db;port=3307;dbname=magento;charset=utf8mb4',
            $configuration->generateDSN()
        );
    }

    #[Test]
    public function specifiesDefaultCredentials()
    {
        $configuration = MySQLConfiguration::fromHost('db', 'magento');
        $this->assertEquals('root', $configuration->user);
        $this->assertEquals('', $configuration->password);
    }

    #[Test]
    public function overridesDefaultCredentials()
    {
        $configuration = MySQLConfiguration::fromHost('db', 'magento')
            ->withCredentials('magento', 'password');

        $this->assertEquals('magento', $configuration->user);
        $this->assertEquals('password', $configuration->password);
    }

    #[Test]
    public function generatesDefaultDriverOptions()
    {
        $configuration = MySQLConfiguration::fromHost('db', 'magento');
        $this->assertEquals(
            [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET SQL_MODE=\'NO_AUTO_VALUE_ON_ZERO\',FOREIGN_KEY_CHECKS=0,NAMES utf8mb4',
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false
            ],
            $configuration->generateDriverOptions()
        );
    }

    #[Test]
    public function customCharsetAffectsBothDnsAndDriverOptions()
    {
        $configuration = MySQLConfiguration::fromHost('db', 'magento')
            ->withCharset('utf8mb3');

        $this->assertStringContainsString('charset=utf8mb3', $configuration->generateDSN());
        $this->assertEquals(
            'SET SQL_MODE=\'NO_AUTO_VALUE_ON_ZERO\',FOREIGN_KEY_CHECKS=0,NAMES utf8mb3',
            $configuration->generateDriverOptions()[PDO::MYSQL_ATTR_INIT_COMMAND]
        );
    }

    #[Test]
    public function settingsServerKeyAddsItToDriverOptions()
    {
        $configuration = MySQLConfiguration::fromHost('db', 'magento')
            ->withServerKey('path/to/server/key.ca');

        $this->assertEquals(
            'path/to/server/key.ca',
            $configuration->generateDriverOptions()[PDO::MYSQL_ATTR_SERVER_PUBLIC_KEY]
        );
    }
}
