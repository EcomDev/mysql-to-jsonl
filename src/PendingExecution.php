<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;

use Amp\Future;
use Amp\Parallel\Worker\Execution;

class PendingExecution
{
    /* @var Future[] */
    private array $futures = [];

    /* @var Execution[] */
    private array $executors = [];

    public function pushExecution(Execution $execution): void
    {
        $this->executors[] = $execution;
    }

    public function pushFuture(Future $future): void
    {
        $this->futures[] = $future;
    }

    public function await(): void
    {
        $futures = $this->futures;
        $this->futures = [];
        Future\awaitAll($futures);
        $executors = $this->executors;
        foreach ($executors as $executor) {
            $executor->await();
        }
    }

    public function waitIfLimited(int $maxExecutors): void
    {
        if ($maxExecutors === count($this->futures)) {
            foreach ($this->futures as $index => $future) {
                if ($future->isComplete()) {
                    unset($this->futures[$index]);
                    return;
                }
            }
            foreach (Future::iterate($this->futures) as $future) {
                $future->await();
                break;
            };
        }
    }
}
