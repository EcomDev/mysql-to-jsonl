<?php

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\TableEntry;
use SplFileObject;

final class JsonImportSource implements ImportSource
{
    private array $header;

    public function __construct(private readonly SplFileObject $fileObject)
    {
        $this->header = $this->readJsonLine();
    }

    public function header(): array
    {
        return $this->header;
    }

    public function rows(): iterable
    {
        $rowNumber = 0;
        while ($row = $this->readJsonLine(false)) {
            $rowNumber++;
            if (count($row) != count($this->header)) {
                throw new \LogicException(
                    sprintf(
                        'Column count does not match header on row %s in %s',
                        $rowNumber,
                        $this->fileObject->getRealPath()
                    )
                );
            }
            yield $row;
        }
    }

    private function readJsonLine(bool $strict = true): mixed
    {
        try {
            $line = $this->fileObject->fgets();
            if (!$line && !$strict) {
                return false;
            }
            return json_decode(
                $line,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $e) {
            throw new \LogicException(
                sprintf('Failed to parse JSON file: %s', $this->fileObject->getRealPath()),
                $e->getCode(),
                $e
            );
        }
    }
}
