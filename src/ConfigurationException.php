<?php

namespace EcomDev\MySQL2JSONL;

use JsonSchema\Exception\InvalidSchemaException;
use RuntimeException;
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

    public function output(OutputInterface $output): void
    {

    }
}