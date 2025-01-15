<?php

namespace EcomDev\MySQL2JSONL\Import;

use DirectoryIterator;
use EcomDev\MySQL2JSONL\Configuration;
use EcomDev\MySQL2JSONL\TableEntry;
use SplFileObject;

final readonly class JsonImportSourceFactory implements ImportSourceFactory
{
    public function __construct(private string $inputPath, private Configuration $configuration)
    {
        if (!is_dir($this->inputPath)) {
            throw new \InvalidArgumentException("Input source is not valid directory");
        }
    }
    public function create(TableEntry $table): ImportSource
    {
        $file = sprintf('%s/%s.jsonl', $this->inputPath, $table->name);
        return new JsonImportSource(new SplFileObject($file, 'r'));
    }

    public function listTables(): iterable
    {
        foreach (new DirectoryIterator($this->inputPath) as $file) {
            if ($file->isFile() && $file->getExtension() === 'jsonl') {
                $file = new SplFileObject($file->getRealPath(), 'r');
                $file->seek(PHP_INT_MAX);
                $rowCount = $file->key() - 1;
                $table =  new TableEntry($file->getBasename('.jsonl'), $rowCount);
                $isSkipped = !$this->configuration->includeCondition->isSatisfiedBy($table)
                    || $this->configuration->excludeCondition->isSatisfiedBy($table);
                if ($isSkipped) {
                    continue;
                }
                yield $table;
            }
        }
    }
}
