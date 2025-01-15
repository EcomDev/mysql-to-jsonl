<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

final class ExportOutputColumCountDoesNotMatchException extends \LogicException
{
    public function __construct()
    {
        parent::__construct('Column count does not match header');
    }
}
