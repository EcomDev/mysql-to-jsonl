<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Progress;

use EcomDev\MySQL2JSONL\ProgressNotifier;

enum WorkerMessageType
{
    case Start;
    case Update;
    case Finish;

    public function process(ProgressNotifier $notifier, array $payload): void
    {
        match ($this) {
            self::Start => $notifier->start(...$payload),
            self::Update => $notifier->update(...$payload),
            self::Finish => $notifier->finish(...$payload),
        };
    }
}
