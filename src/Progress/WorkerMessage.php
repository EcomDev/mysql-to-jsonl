<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Progress;

use EcomDev\MySQL2JSONL\ProgressNotifier;

final readonly class WorkerMessage
{
    public function __construct(
        public WorkerMessageType $type,
        public array $data
    ) {
    }

    public function process(ProgressNotifier $progressNotifier): void
    {
        $this->type->process($progressNotifier, $this->data);
    }
}
