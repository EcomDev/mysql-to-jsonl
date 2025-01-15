<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;

use Symfony\Component\Console\Formatter\NullOutputFormatterStyle;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

class TestableConsoleOutput extends StreamOutput implements ConsoleOutputInterface
{
    private OutputInterface $errorOutput;

    private array $consoleSectionOutputs = [];

    public function __construct(
        private $stdOutput,
        private $stdError
    ) {
        parent::__construct($this->stdOutput, decorated: true);
        $this->getFormatter()->setStyle('error', new NullOutputFormatterStyle());
        $this->getFormatter()->setStyle('info', new NullOutputFormatterStyle());
        $this->getFormatter()->setStyle('warning', new NullOutputFormatterStyle());
        $this->errorOutput = new StreamOutput($this->stdError, decorated: true, formatter: $this->getFormatter());
    }

    public static function create(): self
    {
        return new self(
            fopen('php://temp/maxmemory:8192', 'r+'),
            fopen('php://temp/maxmemory:8192', 'r+')
        );
    }

    public function getErrorOutput(): OutputInterface
    {
        return $this->errorOutput;
    }

    public function setErrorOutput(OutputInterface $error): void
    {
        $this->errorOutput = $error;
    }

    public function section(): ConsoleSectionOutput
    {
        return new ConsoleSectionOutput(
            $this->stdOutput,
            $this->consoleSectionOutputs,
            $this->getVerbosity(),
            $this->isDecorated(),
            $this->getFormatter()
        );
    }

    public function flushError(): array
    {
        return $this->readValue($this->stdError);
    }

    public function flushOutput(): array
    {
        return $this->readValue($this->stdOutput);
    }

    private function readValue($input): array
    {
        $position = ftell($input);
        rewind($input);

        $lines = (object)['lines' => [], 'cursor' => 0];
        while ($line = fgets($input)) {
            $lines->cursor = count($lines->lines);
            $line = preg_replace_callback(
                "/\x1b\[(\d+A|0J)/",
                fn ($matches) => $this->processEscapeCommand($matches[1], $lines),
                $line
            );
            $lines->lines[] = rtrim($line, "\n");
        }

        fseek($input, $position);
        return $lines->lines;
    }

    private function processEscapeCommand(string $command, object $lines): string
    {
        if (str_ends_with($command, 'J')) {
            array_splice($lines->lines, -$lines->cursor);
        }

        if (str_ends_with($command, 'A')) {
            $lines->cursor -= (int)substr($command, 0, -1);
        }

        return '';
    }
}
