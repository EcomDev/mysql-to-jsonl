<?php

namespace EcomDev\MySQL2JSONL;

use EcomDev\TestContainers\MagentoData\DbContainer;
use EcomDev\TestContainers\MagentoData\DbContainerBuilder;

function magentoWithSampleData(string $sharedId): Configuration
{
    $connection = DbContainerBuilder::mysql()
        ->withMagentoVersion('2.4.7-p3')
        ->withSampleData()
        ->shared($sharedId)
        ->getConnectionSettings();

    return Configuration::fromMySQLConfiguration(
        MySQLConfiguration::fromHost(
            $connection->host,
            $connection->database,
        )
        ->withPort($connection->port)
        ->withCredentials(
            $connection->user,
            $connection->password
        ),
        10
    );
}


readonly class ContainerWithConfig
{
    public function __construct(
        public Configuration $configuration,
        public DbContainer   $container
    )
    {

    }
}

function magentoWithoutData(): ContainerWithConfig
{
    $container = DbContainerBuilder::mysql()
        ->withMagentoVersion('2.4.7-p3')
        ->withSampleData()
        ->build();

    $connection = $container->getConnectionSettings();

    $config = Configuration::fromMySQLConfiguration(
        MySQLConfiguration::fromHost(
            $connection->host,
            $connection->database,
        )
            ->withPort($connection->port)
            ->withCredentials(
                $connection->user,
                $connection->password
            ),
        10
    );

    return new ContainerWithConfig(
        $config,
        $container,
    );
}