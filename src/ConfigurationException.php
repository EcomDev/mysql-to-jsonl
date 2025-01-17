<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;

use JsonSchema\Exception\InvalidSchemaException;
use RuntimeException;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ConfigurationException extends RuntimeException implements \Throwable
{
    private function __construct(
        private readonly array $errors
    ) {
        parent::__construct('Configuration validation failed');
    }

    /**
     * Creates message from errors
     *
     * @param string[] $errors
     */
    public static function fromErrors(array $errors): self
    {
        return new self($errors);
    }

    public function output(ConsoleOutputInterface $output): void
    {
        $helper = new FormatterHelper();
        $messages = [];

        foreach ($this->errors as $path => $error) {
            $messages[] = sprintf('%s: %s', $path, $error);
        }

        $output->getErrorOutput()->writeln(
            $helper->formatSection($this->getMessage(), PHP_EOL . implode(PHP_EOL, $messages), 'error')
        );
    }
}
