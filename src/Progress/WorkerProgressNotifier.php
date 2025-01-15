<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Progress;

use Amp\Sync\Channel;
use EcomDev\MySQL2JSONL\ProgressNotifier;

final class WorkerProgressNotifier implements ProgressNotifier
{
    public function __construct(
        private readonly Channel $channel,
        private readonly float $freq = 0.3,
        private int $total = 0
    ) {
    }

    public function start(string $name, int $total): void
    {
        $this->total = $total;
        $this->channel->send(new WorkerMessage(
            WorkerMessageType::Start,
            [$name, $total]
        ));
    }

    public function update(string $name, int $current): void
    {
        if ($this->total > 0 && $this->freq < 1 && $current % ceil($this->total * $this->freq) > 0) {
            return;
        }

        $this->channel->send(new WorkerMessage(
            WorkerMessageType::Update,
            [$name, $current]
        ));
    }

    public function finish(string $name): void
    {
        $this->channel->send(new WorkerMessage(
            WorkerMessageType::Finish,
            [$name]
        ));
    }
}
