<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Import;

use EcomDev\MySQL2JSONL\TempDirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class JsonImportSourceTest extends TestCase
{
    use TempDirectoryFixture;

    #[Test]
    public function emptyFileErrorsOut()
    {
        $file = $this->file();
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Failed to parse JSON file: ' . $this->file);
        new JsonImportSource($file);
    }

    #[Test]
    public function readsLineByLineAfterHeader()
    {
        $file = $this->file();
        $file->fwrite(implode(PHP_EOL, [
            '["header","column"]',
            '["value1","value2"]',
            '["value3","value4"]',
            '["value5","value6"]',
            ''
        ]));


        $source = new JsonImportSource(new \SplFileObject($file->getRealPath(), 'r'));
        $this->assertEquals(
            [
                ['value1', 'value2'],
                ['value3', 'value4'],
                ['value5', 'value6'],
            ],
            iterator_to_array($source->rows())
        );
    }

    #[Test]
    public function errorsOutOnColumnsNotMatchingRows()
    {
        $file = $this->file();
        $file->fwrite(implode(PHP_EOL, [
            '["header","column"]',
            '["value1","value2"]',
            '["value3"]',
            '["value5","value6"]',
            ''
        ]));


        $source = new JsonImportSource(new \SplFileObject($file->getRealPath(), 'r'));
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Column count does not match header on row 2 in ' . $this->file);
        iterator_to_array($source->rows());
    }
}
