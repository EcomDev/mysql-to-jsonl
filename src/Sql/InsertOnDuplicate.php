<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Sql;

final class InsertOnDuplicate
{
    public function generate(string $tableName, array $columns, int $rowCount, $onUpdate = []): string
    {
        $sqlOnUpdate = '';

        if ($onUpdate) {
            $sqlOnUpdate = sprintf(
                ' ON DUPLICATE KEY UPDATE %s',
                implode(
                    ',',
                    array_map(fn ($column) => "`$column` = VALUES(`$column`)", $onUpdate)
                )
            );
        }

        $rowLine = rtrim(str_repeat('?,', count($columns)), ',');
        $rowLines = str_repeat(
            "($rowLine),",
            $rowCount
        );

        $sql = sprintf(
            'INSERT INTO `%s` (%s) VALUES %s%s',
            $tableName,
            implode(',', array_map(fn ($column) => "`$column`", $columns)),
            rtrim(
                $rowLines,
                ','
            ),
            $sqlOnUpdate
        );

        return $sql;
    }
}
