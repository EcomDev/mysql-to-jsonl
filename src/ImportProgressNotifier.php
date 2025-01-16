<?php

namespace EcomDev\MySQL2JSONL;

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

    public function __construct(private readonly ConsoleOutputInterface $output)
    {
    }

    public function start(string $name, int $total): void
    {
        $progressBar = new ProgressBar($this->output, $total);
        $progressBar->setProgressCharacter('🚀');
        $progressBar->setBarCharacter('-');
        $progressBar->setEmptyBarCharacter(' ');
        $this->progressBars[$name] = $progressBar;

    }

    public function update(string $name, int $current): void
    {
        $this->progressBars[$name]->advance($current);
    }

    public function finish(string $name): void
    {
        $this->progressBars[$name]->finish();
    }
}