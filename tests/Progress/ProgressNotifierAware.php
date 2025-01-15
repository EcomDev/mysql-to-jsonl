<?php

namespace EcomDev\MySQL2JSONL\Progress;

trait ProgressNotifierAware
{
    private array $progress = [];

    public function start(string $name, int $total): void
    {
        $this->progress[] = ['start', $name, $total];
    }

    public function update(string $name, int $current): void
    {
        $this->progress[] = ['update', $name, $current];
    }

    public function finish(string $name): void
    {
        $this->progress[] = ['finish', $name];
    }
}
