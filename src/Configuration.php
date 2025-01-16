<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;

use Amp\Mysql\MysqlConfig;
use EcomDev\MySQL2JSONL\Condition\AndTableCondition;
use EcomDev\MySQL2JSONL\Condition\StaticTableCondition;
use EcomDev\MySQL2JSONL\Condition\TableNameCondition;
use EcomDev\MySQL2JSONL\Condition\TableRowsCondition;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use JsonException;

final readonly class Configuration
{
    private function __construct(
        public MysqlConfig $connection,
        public TableCondition $includeCondition,
        public TableCondition $excludeCondition,
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
        $result = $validator->validate($data, $schema, Constraint::CHECK_MODE_APPLY_DEFAULTS);

        if ($result !== Validator::ERROR_NONE) {
            $errors = array_reduce(
                $validator->getErrors(),
                function ($errors, $error) {
                    $errors[$error['property']] = $error['message'];
                    return $errors;
                }
            );

            throw ConfigurationException::fromErrors($errors);
        }

        $mysqlConfig = new MysqlConfig(
            host: $data->connection->host,
            port: (int) $data->connection->port,
            user: $data->connection->user,
            password: $data->connection->password,
            database: $data->connection->database,
        );

        if (isset($data->connection->key)) {
            $mysqlConfig = $mysqlConfig->withKey($data->connection->key);
        }

        if ($data->connection->useCompression) {
            $mysqlConfig = $mysqlConfig->withCompression();
        }

        $includeCondition = StaticTableCondition::alwaysTrue();
        $excludeCondition = StaticTableCondition::alwaysFalse();

        foreach ($data->includeTables as $include) {
            $includeCondition = $includeCondition->withCondition(self::buildCondition($include));
        }

        foreach ($data->excludeTables as $include) {
            $excludeCondition = $excludeCondition->withCondition(self::buildCondition($include));
        }

        return new self(
            $mysqlConfig
                ->withCharset(
                    $data->connection->charset,
                    $data->connection->collate
                )
                ->withSqlMode(
                    $data->connection->sqlMode,
                ),
            $includeCondition,
            $excludeCondition,
        );
    }

    public static function fromMySQLConfig(MysqlConfig $config): self
    {
        return new self($config, StaticTableCondition::alwaysTrue(), StaticTableCondition::alwaysFalse());
    }

    public function withIncludeCondition(TableCondition $condition): self
    {
        return new self(
            $this->connection,
            $this->includeCondition->withCondition($condition),
            $this->excludeCondition
        );
    }

    public function withExcludeCondition(TableCondition $condition): self
    {
        return new self(
            $this->connection,
            $this->includeCondition,
            $this->excludeCondition->withCondition($condition)
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
}
