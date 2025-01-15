<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

final class ExportOutputWrittenBeforeOpenException extends \LogicException
{
    public function __construct()
    {
        parent::__construct('Output was written before open');
    }
}
