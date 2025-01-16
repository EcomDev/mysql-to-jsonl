<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Progress;

use EcomDev\MySQL2JSONL\ProgressNotifier;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

final class ImportProgressNotifier implements ProgressNotifier
{
    /**
     * Progress bar per output component
     *
     * @var ProgressBar[]
     */
    private array $progressBars = [];

    public function __construct(
        private readonly ConsoleOutputInterface $output,
        private readonly float $redrawFreq = 1 / 3,
    ) {
    }

    public function withRealtime(): self
    {
        return new self($this->output, 0);
    }

    public function start(string $name, int $total): void
    {
        $section = $this->output->section();
        $progressBar = new ProgressBar($section, $total, $this->redrawFreq);
        $progressBar->setProgressCharacter('🚀');
        $progressBar->setFormat('%message% `%table%` [%bar%] %percent%% ');
        $progressBar->setMessage($name, 'table');
        $progressBar->setMessage('Importing');
        $this->progressBars[$name] = $progressBar;
        $progressBar->start();
    }

    public function update(string $name, int $current): void
    {
        $this->progressBars[$name]->advance($current);
    }

    public function finish(string $name): void
    {
        $this->progressBars[$name]->setMessage('Imported');
        $this->progressBars[$name]->finish();
    }
}
