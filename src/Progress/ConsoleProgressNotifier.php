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
use Symfony\Component\Console\Output\ConsoleSectionOutput;

final class ConsoleProgressNotifier implements ProgressNotifier
{
    /**
     * Progress bar per output component
     *
     * @var ProgressBar[]
     */
    private array $progressBars = [];

    /**
     * List of console sections that are used
     *
     * @var ConsoleSectionOutput[]
     */
    private array $progressBarSections = [];

    /**
     * List of re-usable console sections
     *
     * @var ConsoleSectionOutput[]
     */
    private array $reusableSections = [];

    /**
     * Section for outputting all completed progress bars
     *
     * @var ConsoleSectionOutput
     */
    private ConsoleSectionOutput $sectionOutput;

    public function __construct(
        private readonly ConsoleOutputInterface $output,
        private readonly string $inProgressMessage,
        private readonly string $finishedMessage,
        private readonly float $redrawFreq = 1 / 3,
    ) {
        $this->sectionOutput = $this->output->section();
    }

    public function withRealtime(): self
    {
        return new self($this->output, $this->inProgressMessage, $this->finishedMessage, 0);
    }

    public function start(string $name, int $total): void
    {
        $section = $this->reusableSections ? array_shift($this->reusableSections) : $this->output->section();
        $progressBar = new ProgressBar($section, $total, $this->redrawFreq);
        $progressBar->setProgressCharacter('🚀');
        $progressBar->setFormat('%message% `%table%` [%bar%] %percent%% ');
        $progressBar->setMessage($name, 'table');
        $progressBar->setMessage($this->inProgressMessage);
        $this->progressBars[$name] = $progressBar;
        $this->progressBarSections[$name] = $section;
        $progressBar->start();
    }

    public function update(string $name, int $current): void
    {
        $this->progressBars[$name]->advance($current);
    }

    public function finish(string $name): void
    {
        $total = $this->progressBars[$name]->getMaxSteps();
        $this->progressBars[$name]->finish();
        $this->progressBarSections[$name]->clear();
        $this->reusableSections[] = $this->progressBarSections[$name];
        unset($this->progressBars[$name]);
        unset($this->progressBarSections[$name]);

        $this->sectionOutput->writeln(
            sprintf('%s %d rows in `%s`', $this->finishedMessage, $total, $name),
        );
    }
}
