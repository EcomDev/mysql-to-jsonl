<?php

namespace EcomDev\MySQL2JSONL;

interface ProgressNotifier
{
    public function start(string $name, int $total): void;

    public function update(string $name, int $current): void;

    public function finish(string $name): void;
}