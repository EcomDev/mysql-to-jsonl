<?php

namespace EcomDev\MySQL2JSONL;

use Amp\Future;
use Amp\Mysql\MysqlConfig;
use Amp\Pipeline\Pipeline;
use Amp\Pipeline\Queue;
use EcomDev\TestContainers\MagentoData\DbContainerBuilder;
use Revolt\EventLoop;
use function Amp\async;

function magentoWithSampleData(string $sharedId): Configuration
{
    $connection = DbContainerBuilder::mysql()
        ->withSampleData()
        ->shared($sharedId)
        ->getConnectionSettings();

    return Configuration::fromMySQLConfig(
        new MysqlConfig(
            host: $connection->host,
            port: $connection->port,
            user: $connection->user,
            password: $connection->password,
            database: $connection->database
        )
    );
}

function queueToArray(Queue $queue): array
{
    $result = [];
    foreach ($queue->iterate() as $item) {
        $result[] = $item;
    }
    return $result;
}