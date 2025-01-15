<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;

use Amp\Mysql\MysqlConfig;
use Amp\Mysql\MysqlConnectionPool;
use EcomDev\MySQL2JSONL\Condition\AndTableCondition;
use EcomDev\MySQL2JSONL\Condition\InitialTableCondition;
use EcomDev\MySQL2JSONL\Condition\TableNameCondition;
use EcomDev\MySQL2JSONL\Condition\TableRowsCondition;
use EcomDev\MySQL2JSONL\Import\ImportMode;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use JsonException;
use PDO;

final readonly class Configuration
{
    private function __construct(
        public MySQLConfiguration $connection,
        public TableCondition $includeCondition,
        public TableCondition $excludeCondition,
        public ImportMode $importMode,
        public int $concurrency,
        public int $batchSize
    ) {
    }

    /**
     * Creates configuration based on JSON value supported by schema.json
     *
     * @throws ConfigurationException|JsonException
     */
    public static function fromJSON(string $json): Configuration
    {
        $validator = new Validator();
        $schema = json_decode(
            file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR .  'schema.json'),
            flags: JSON_THROW_ON_ERROR
        );
        $data = json_decode(
            $json,
            flags: JSON_THROW_ON_ERROR
        );

        $validator->validate($data, $schema, Constraint::CHECK_MODE_APPLY_DEFAULTS);

        if (!$validator->isValid()) {
            $errors = array_reduce(
                $validator->getErrors(),
                function ($errors, $error) {
                    $errors[$error['property']] = $error['message'];
                    return $errors;
                }
            );

            throw ConfigurationException::fromErrors($errors);
        }

        $data = $data->config;

        $mysqlConfig = !empty($data->connection->unixSocket) ?
            MySQLConfiguration::fromUnixSocket(
                $data->connection->unixSocket,
                $data->connection->database
            ) :
            MySQLConfiguration::fromHost(
                $data->connection->host,
                $data->connection->database
            );

        $mysqlConfig = $mysqlConfig
            ->withPort($data->connection->port)
            ->withCharset($data->connection->charset)
            ->withCredentials($data->connection->user, $data->connection->password);

        if (isset($data->connection->key)) {
            $mysqlConfig = $mysqlConfig->withServerKey($data->connection->key);
        }

        $includeCondition = InitialTableCondition::alwaysTrue();
        $excludeCondition = InitialTableCondition::alwaysFalse();

        foreach ($data->includeTables as $include) {
            $includeCondition = $includeCondition->withCondition(self::buildCondition($include));
        }

        foreach ($data->excludeTables as $include) {
            $excludeCondition = $excludeCondition->withCondition(self::buildCondition($include));
        }

        return new self(
            $mysqlConfig,
            $includeCondition,
            $excludeCondition,
            $data->importMode === 'update' ? ImportMode::Update : ImportMode::Truncate,
            $data->concurrency,
            $data->batchSize
        );
    }
    public static function fromMySQLConfiguration(MySQLConfiguration $mysqlConfiguration, int $concurrency): self
    {
        return new self(
            $mysqlConfiguration,
            InitialTableCondition::alwaysTrue(),
            InitialTableCondition::alwaysFalse(),
            ImportMode::Truncate,
            $concurrency,
            1000
        );
    }

    public function withIncludeCondition(TableCondition $condition): self
    {
        return new self(
            $this->connection,
            $this->includeCondition->withCondition($condition),
            $this->excludeCondition,
            $this->importMode,
            $this->concurrency,
            $this->batchSize
        );
    }

    public function withExcludeCondition(TableCondition $condition): self
    {
        return new self(
            $this->connection,
            $this->includeCondition,
            $this->excludeCondition->withCondition($condition),
            $this->importMode,
            $this->concurrency,
            $this->batchSize
        );
    }

    private static function buildCondition(string|object $condition): TableCondition
    {
        if (is_string($condition)) {
            return TableNameCondition::exactMatch($condition);
        }

        foreach ($condition as $key => $value) {
            return match ($key) {
                'startsWith' => TableNameCondition::startsWith($value),
                'endsWith' => TableNameCondition::endsWith($value),
                'contains' => TableNameCondition::contains($value),
                'regexp' => TableNameCondition::regexp($value),
                'minRows' => TableRowsCondition::minRows($value),
                'maxRows' => TableRowsCondition::maxRows($value),
                'and' => AndTableCondition::from(
                    ...array_map(fn ($condition) => self::buildCondition($condition), $value)
                )
            };
        }
    }

    public function createPDOConnection(): PDO
    {
        return new PDO(
            $this->connection->generateDSN(),
            $this->connection->user,
            $this->connection->password,
            $this->connection->generateDriverOptions()
        );
    }

    public function withImportMode(Import\ImportMode $mode): self
    {
        return new self(
            $this->connection,
            $this->includeCondition,
            $this->excludeCondition,
            $mode,
            $this->concurrency,
            $this->batchSize
        );
    }

    public function withBatchSize(int $batchSize): self
    {
        return new self(
            $this->connection,
            $this->includeCondition,
            $this->excludeCondition,
            $this->importMode,
            $this->concurrency,
            $batchSize
        );
    }

    public function withConcurrency(int $concurrency): self
    {
        return new self(
            $this->connection,
            $this->includeCondition,
            $this->excludeCondition,
            $this->importMode,
            $concurrency,
            $this->batchSize
        );
    }
}
