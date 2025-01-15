<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Progress;

use Amp\Sync\Channel;
use Amp\Sync\ChannelException;
use EcomDev\MySQL2JSONL\ProgressNotifier;

final readonly class WorkerProgressConsumer
{
    public function __construct(private ProgressNotifier $notifier)
    {
    }

    public function process(Channel $channel)
    {
        try {
            while ($message = $channel->receive()) {
                $message->process($this->notifier);
            }
        } catch (ChannelException $e) {
        }
    }
}
