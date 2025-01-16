<?php

namespace EcomDev\MySQL2JSONL;

use Amp\Mysql\MysqlConfig;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use JsonException;

final readonly class Configuration
{
    private function __construct(
        public MysqlConfig $connection,
        public TableCondition $includeCondition,
        public TableCondition $excludeCondition,
    )
    {

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

        if (($result & Validator::ERROR_DOCUMENT_VALIDATION) === Validator::ERROR_DOCUMENT_VALIDATION) {
            $errors = array_reduce(
                $validator->getErrors(),
                function ($errors, $error) {
                    $errors[$error['property']] = $error['message'];
                    return $errors;
                }
            );

            throw ConfigurationException::fromErrors($errors);
        }


        return new self(
            new MysqlConfig(
                host: $data->connection->host,
                port: (int) $data->connection->port,
                user: $data->connection->user,
                password: $data->connection->password,
                database: $data->connection->database,
            ),
            StaticTableCondition::alwaysTrue(),
            StaticTableCondition::alwaysFalse(),
        );
    }

    public static function fromMySQLConfig(MysqlConfig $config): self
    {
        return new self($config, StaticTableCondition::alwaysTrue(), StaticTableCondition::alwaysFalse());
    }

    public function withIncludeCondition(TableCondition $condition): self
    {

    }

    public function withExcludeCondition(TableCondition $condition): self
    {

    }
}